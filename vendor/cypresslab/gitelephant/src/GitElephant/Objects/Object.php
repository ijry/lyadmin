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

use \GitElephant\Command\RevParseCommand;
use \GitElephant\Repository;

/**
 * A Object instance represents a node in the git tree repository
 * It could be a file or a folder, as well as a submodule (a "link" in the git language)
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class Object implements TreeishInterface
{
    const TYPE_BLOB = 'blob';
    const TYPE_TREE = 'tree';
    const TYPE_LINK = 'commit';

    /**
     * @var \GitElephant\Repository
     */
    protected $repository;

    /**
     * permissions
     *
     * @var string
     */
    private $permissions;

    /**
     * type
     *
     * @var string
     */
    private $type;

    /**
     * sha
     *
     * @var string
     */
    private $sha;

    /**
     * size
     *
     * @var string
     */
    private $size;

    /**
     * name
     *
     * @var string
     */
    private $name;

    /**
     * path
     *
     * @var string
     */
    private $path;

    /**
     * create a Object from a single outputLine of the git ls-tree command
     *
     * @param \GitElephant\Repository $repository repository instance
     * @param string                  $outputLine output from ls-tree command
     *
     * @see LsTreeCommand::tree
     * @return Object
     */
    public static function createFromOutputLine(Repository $repository, $outputLine)
    {
        $slices = static::getLineSlices($outputLine);
        $fullPath = $slices['fullPath'];
        if (false === $pos = mb_strrpos($fullPath, '/')) {
            // repository root
            $path = '';
            $name = $fullPath;
        } else {
            $path = substr($fullPath, 0, $pos);
            $name = substr($fullPath, $pos + 1);
        }

        return new static(
            $repository,
            $slices['permissions'],
            $slices['type'],
            $slices['sha'],
            $slices['size'],
            $name,
            $path
        );
    }

    /**
     * Take a line and turn it in slices
     *
     * @param string $line a single line output from the git binary
     *
     * @return array
     */
    public static function getLineSlices($line)
    {
        preg_match('/^(\d+) (\w+) ([a-z0-9]+) +(\d+|-)\t(.*)$/', $line, $matches);
        $permissions = $matches[1];
        $type        = null;
        switch ($matches[2]) {
            case Object::TYPE_TREE:
                $type = Object::TYPE_TREE;
                break;
            case Object::TYPE_BLOB:
                $type = Object::TYPE_BLOB;
                break;
            case Object::TYPE_LINK:
                $type = Object::TYPE_LINK;
                break;
        }
        $sha      = $matches[3];
        $size     = $matches[4];
        $fullPath = $matches[5];

        return array(
            'permissions' => $permissions,
            'type'        => $type,
            'sha'         => $sha,
            'size'        => $size,
            'fullPath'    => $fullPath
        );
    }

    /**
     * Class constructor
     *
     * @param \GitElephant\Repository $repository  repository instance
     * @param string                  $permissions node permissions
     * @param string                  $type        node type
     * @param string                  $sha         node sha
     * @param string                  $size        node size in bytes
     * @param string                  $name        node name
     * @param string                  $path        node path
     */
    public function __construct(Repository $repository, $permissions, $type, $sha, $size, $name, $path)
    {
        $this->repository  = $repository;
        $this->permissions = $permissions;
        $this->type        = $type;
        $this->sha         = $sha;
        $this->size        = $size;
        $this->name        = $name;
        $this->path        = $path;
    }

    /**
     * toString magic method
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Mime Type getter
     *
     * @param string $basePath the base path of the repository
     *
     * @return string
     */
    public function getMimeType($basePath)
    {
        return mime_content_type($basePath . DIRECTORY_SEPARATOR . $this->path);
    }

    /**
     * get extension if it's a blob
     *
     * @return string|null
     */
    public function getExtension()
    {
        $pos = strrpos($this->name, '.');
        if ($pos === false) {
            return null;
        } else {
            return substr($this->name, $pos+1);
        }
    }

    /**
     * whether the node is a tree
     *
     * @return bool
     */
    public function isTree()
    {
        return self::TYPE_TREE == $this->getType();
    }

    /**
     * whether the node is a link
     *
     * @return bool
     */
    public function isLink()
    {
        return self::TYPE_LINK == $this->getType();
    }

    /**
     * whether the node is a blob
     *
     * @return bool
     */
    public function isBlob()
    {
        return self::TYPE_BLOB == $this->getType();
    }

    /**
     * Full path getter
     *
     * @return string
     */
    public function getFullPath()
    {
        return rtrim(
            '' == $this->path ? $this->name : $this->path.DIRECTORY_SEPARATOR.$this->name,
            DIRECTORY_SEPARATOR
        );
    }

    /**
     * permissions getter
     *
     * @return string
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * sha getter
     *
     * @return string
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * type getter
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * path getter
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * size getter
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * gets the last commit in this object
     *
     * @return Commit
     */
    public function getLastCommit()
    {
        $log = $this->repository->getLog('HEAD', $this->getFullPath(), 1);
        return $log[0];
    }

    /**
     * rev-parse command - often used to return a commit tag.
     *
     * @param array         $options the options to apply to rev-parse
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return array
     */
    public function revParse(Array $options = array())
    {
        $c = RevParseCommand::getInstance()->revParse($this, $options);
        $caller = $this->repository->getCaller();
        $caller->execute($c);

        return array_map('trim', $caller->getOutputLines(true));
    }

    /*
     * Repository setter
     *
     * @param \GitElephant\Repository $repository the repository variable
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Repository getter
     *
     * @return \GitElephant\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
