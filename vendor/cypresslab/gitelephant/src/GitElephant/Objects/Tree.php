<?php
/**
 * GitElephant - An abstraction layer for git written in PHP
 * Copyright (C) 2013  Matteo Giachino
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see [http://www.gnu.org/licenses/].
 */

namespace GitElephant\Objects;

use \GitElephant\Repository;
use \GitElephant\Command\LsTreeCommand;
use \GitElephant\Command\CatFileCommand;

/**
 * An abstraction of a git tree
 *
 * Retrieve an object with array access, iterable and countable
 * with a collection of Object at the given path of the repository
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class Tree extends Object implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var string
     */
    private $ref;

    /**
     * the cursor position
     *
     * @var int
     */
    private $position;

    /**
     * the tree subject
     *
     * @var Object
     */
    private $subject;

    /**
     * tree children
     *
     * @var array
     */
    private $children = array();

    /**
     * tree path children
     *
     * @var array
     */
    private $pathChildren = array();

    /**
     * the blob of the actual tree
     *
     * @var \GitElephant\Objects\Object
     */
    private $blob;

    /**
     * static method to generate standalone log
     *
     * @param \GitElephant\Repository $repository  repo
     * @param array                   $outputLines output lines from command.log
     *
     * @return \GitElephant\Objects\Log
     */
    public static function createFromOutputLines(Repository $repository, $outputLines)
    {
        $tree = new self($repository);
        $tree->parseOutputLines($outputLines);

        return $tree;
    }

    /**
     * get the commit properties from command
     *
     * @see LsTreeCommand::tree
     */
    private function createFromCommand()
    {
        $command = LsTreeCommand::getInstance($this->getRepository())->tree($this->ref, $this->subject);
        $outputLines = $this->getCaller()->execute($command)->getOutputLines(true);
        $this->parseOutputLines($outputLines);
    }

    /**
     * Some path examples:
     *    empty string for root
     *    folder1/folder2
     *    folder1/folder2/filename
     *
     * @param \GitElephant\Repository $repository the repository
     * @param string                  $ref        a treeish reference
     * @param Object                  $subject    the subject
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @internal param \GitElephant\Objects\Object|string $treeObject Object instance
     */
    public function __construct(Repository $repository, $ref = 'HEAD', $subject = null)
    {
        $this->position   = 0;
        $this->repository = $repository;
        $this->ref = $ref;
        $this->subject = $subject;
        $this->createFromCommand();
    }

    /**
     * parse the output of a git command showing a ls-tree
     *
     * @param array $outputLines output lines
     */
    private function parseOutputLines($outputLines)
    {
        foreach ($outputLines as $line) {
            $this->parseLine($line);
        }
        usort($this->children, array($this, 'sortChildren'));
        $this->scanPathsForBlob($outputLines);
    }

    /**
     * @return \GitElephant\Command\Caller\Caller
     */
    private function getCaller()
    {
        return $this->getRepository()->getCaller();
    }

    /**
     * get the current tree parent, null if root
     *
     * @return null|string
     */
    public function getParent()
    {
        if ($this->isRoot()) {
            return null;
        }

        return substr($this->subject->getFullPath(), 0, strrpos($this->subject->getFullPath(), '/'));
    }

    /**
     * tell if the tree created is the root of the repository
     *
     * @return bool
     */
    public function isRoot()
    {
        return null === $this->subject;
    }

    /**
     * tell if the path given is a blob path
     *
     * @return bool
     */
    public function isBlob()
    {
        return isset($this->blob);
    }

    /**
     * the current tree path is a binary file
     *
     * @return bool
     */
    public function isBinary()
    {
        return $this->isRoot() ? false : Object::TYPE_BLOB === $this->subject->getType();
    }

    /**
     * get binary data
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return string
     */
    public function getBinaryData()
    {
        $cmd = CatFileCommand::getInstance($this->getRepository())->content($this->getSubject(), $this->ref);

        return $this->getCaller()->execute($cmd)->getRawOutput();
    }

    /**
     * Return an array like this
     *   0 => array(
     *      'path' => the path to the current element
     *      'label' => the name of the current element
     *   ),
     *   1 => array(),
     *   ...
     *
     * @return array
     */
    public function getBreadcrumb()
    {
        $bc = array();
        if (!$this->isRoot()) {
            $arrayNames = explode('/', $this->subject->getFullPath());
            $pathString = '';
            foreach ($arrayNames as $i => $name) {
                if ($this->isBlob() && $name == $this->blob->getName()) {
                    $bc[$i]['path']  = $pathString . $name;
                    $bc[$i]['label'] = $this->blob;
                    $pathString .= $name . '/';
                } else {
                    $bc[$i]['path']  = $pathString . $name;
                    $bc[$i]['label'] = $name;
                    $pathString .= $name . '/';
                }
            }
        }

        return $bc;
    }

    /**
     * check if the path is equals to a fullPath
     * to tell if it's a blob
     *
     * @param array $outputLines output lines
     *
     * @return mixed
     */
    private function scanPathsForBlob($outputLines)
    {
        // no children, empty folder or blob!
        if (count($this->children) > 0) {
            return;
        }
        // root, no blob
        if ($this->isRoot()) {
            return;
        }
        if (1 === count($outputLines)) {
            $treeObject = Object::createFromOutputLine($this->repository, $outputLines[0]);
            if ($treeObject->getSha() === $this->subject->getSha()) {
                $this->blob = $treeObject;
            }
        }
    }

    /**
     * Reorder children of the tree
     * Tree first (alphabetically) and then blobs (alphabetically)
     *
     * @param \GitElephant\Objects\Object $a the first object
     * @param \GitElephant\Objects\Object $b the second object
     *
     * @return int
     */
    private function sortChildren(Object $a, Object $b)
    {
        if ($a->getType() == $b->getType()) {
            $names = array($a->getName(), $b->getName());
            sort($names);

            return ($a->getName() == $names[0]) ? -1 : 1;
        }

        return $a->getType() == Object::TYPE_TREE || $b->getType() == Object::TYPE_BLOB ? -1 : 1;
    }

    /**
     * Parse a single line into pieces
     *
     * @param string $line a single line output from the git binary
     *
     * @return mixed
     */
    private function parseLine($line)
    {
        if ($line == '') {
            return;
        }
        $slices = Object::getLineSlices($line);
        if ($this->isBlob()) {
            $this->pathChildren[] = $this->blob->getName();
        } else {
            if ($this->isRoot()) {
                // if is root check for first children
                $pattern     = '/(\w+)\/(.*)/';
                $replacement = '$1';
            } else {
                // filter by the children of the path
                $actualPath = $this->subject->getFullPath();
                if (!preg_match(sprintf('/^%s\/(\w*)/', preg_quote($actualPath, '/')), $slices['fullPath'])) {
                    return;
                }
                $pattern     = sprintf('/^%s\/(\w*)/', preg_quote($actualPath, '/'));
                $replacement = '$1';
            }
            $name = preg_replace($pattern, $replacement, $slices['fullPath']);
            if (strpos($name, '/') !== false) {
                return;
            }
            if (!in_array($name, $this->pathChildren)) {
                $path                 = rtrim(rtrim($slices['fullPath'], $name), '/');
                $treeObject           = new TreeObject(
                    $this->repository,
                    $slices['permissions'],
                    $slices['type'],
                    $slices['sha'],
                    $slices['size'],
                    $name,
                    $path
                );
                $this->children[]     = $treeObject;
                $this->pathChildren[] = $name;
            }
        }
    }

    /**
     * get the last commit message for this tree
     *
     * @param string $ref
     *
     * @throws \RuntimeException
     * @return Commit\Message
     */
    public function getLastCommitMessage($ref = 'master')
    {
        return $this->getLastCommit($ref)->getMessage();
    }

    /**
     * get author of the last commit
     *
     * @param string $ref
     *
     * @throws \RuntimeException
     * @return Author
     */
    public function getLastCommitAuthor($ref = 'master')
    {
        return $this->getLastCommit($ref)->getAuthor();
    }

    /**
     * get the last commit for a given treeish, for the actual tree
     *
     * @param string $ref
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return Commit
     */
    public function getLastCommit($ref = 'master')
    {
        if ($this->isRoot()) {
            return $this->getRepository()->getCommit($ref);
        }
        $log = $this->repository->getObjectLog($this->getObject(), $ref);

        return $log[0];
    }

    /**
     * get the tree object for this tree
     *
     * @return \GitElephant\Objects\Object
     */
    public function getObject()
    {
        if ($this->isRoot()) {
            return null;
        } else {
            return $this->getSubject();
        }
    }

    /**
     * Blob getter
     *
     * @return \GitElephant\Objects\Object
     */
    public function getBlob()
    {
        return $this->blob;
    }

    /**
     * Get Subject
     *
     * @return \GitElephant\Objects\Object
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get Ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->children[$offset]);
    }


    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return isset($this->children[$offset]) ? $this->children[$offset] : null;
    }

    /**
     * ArrayAccess interface
     *
     * @param int   $offset offset
     * @param mixed $value  value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->children[] = $value;
        } else {
            $this->children[$offset] = $value;
        }
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     */
    public function offsetUnset($offset)
    {
        unset($this->children[$offset]);
    }

    /**
     * Countable interface
     *
     * @return int|void
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return $this->children[$this->position];
    }

    /**
     * Iterator interface
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Iterator interface
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Iterator interface
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->children[$this->position]);
    }

    /**
     * Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
