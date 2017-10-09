<?php

/**
 * This file is part of the GitElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package GitElephant\Objects
 *
 * Just for fun...
 */

namespace GitElephant\Objects;

use \GitElephant\Repository;
use \GitElephant\Command\LogRangeCommand;

/**
 * Git range log abstraction object
 *
 * @author Matteo Giachino <matteog@gmail.com>
 * @author John Cartwright <jcartdev@gmail.com>
 * @author Dhaval Patel <tech.dhaval@gmail.com>
 */
class LogRange implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var \GitElephant\Repository
     */
    private $repository;

    /**
     * the commits related to this log
     *
     * @var array
     */
    private $rangeCommits  = array();

    /**
     * the cursor position
     *
     * @var int
     */
    private $position = 0;

    /**
     * Class constructor
     *
     * @param \GitElephant\Repository $repository  repo
     * @param string                  $refStart    starting reference (excluded from the range)
     * @param string                  $refEnd      ending reference
     * @param null                    $path        path
     * @param int                     $limit       limit
     * @param null                    $offset      offset
     * @param boolean                 $firstParent first parent
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function __construct(
        Repository $repository,
        $refStart,
        $refEnd,
        $path = null,
        $limit = 15,
        $offset = null,
        $firstParent = false
    ) {
        $this->repository = $repository;
        $this->createFromCommand($refStart, $refEnd, $path, $limit, $offset, $firstParent);
    }

    /**
     * get the commit properties from command
     *
     * @param string  $refStart    treeish reference
     * @param string  $refEnd      treeish reference
     * @param string  $path        path
     * @param int     $limit       limit
     * @param string  $offset      offset
     * @param boolean $firstParent first parent
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @see ShowCommand::commitInfo
     */
    private function createFromCommand($refStart, $refEnd, $path, $limit, $offset, $firstParent)
    {
        $command = LogRangeCommand::getInstance($this->getRepository())->showLog($refStart, $refEnd, $path, $limit, $offset, $firstParent);
        $outputLines = $this->getRepository()->getCaller()->execute(
            $command,
            true,
            $this->getRepository()->getPath()
        )->getOutputLines(true);
        $this->parseOutputLines($outputLines);
    }

    private function parseOutputLines($outputLines)
    {
        $commitLines = null;
        $this->rangeCommits = array();
        foreach ($outputLines as $line) {
            if (preg_match('/^commit (\w+)$/', $line) > 0) {
                if (null !== $commitLines) {
                    $this->rangeCommits[] = Commit::createFromOutputLines($this->getRepository(), $commitLines);
                }
                $commitLines = array();
            }
            $commitLines[] = $line;
        }
        if (null !== $commitLines && count($commitLines) > 0) {
            $this->rangeCommits[] = Commit::createFromOutputLines($this->getRepository(), $commitLines);
        }
    }

    /**
     * Get array representation
     *
     * @return array
     */
    public function toArray()
    {
        return $this->rangeCommits;
    }

    /**
     * Get the first commit
     *
     * @return Commit|null
     */
    public function first()
    {
        return $this->offsetGet(0);
    }

    /**
     * Get the last commit
     *
     * @return Commit|null
     */
    public function last()
    {
        return $this->offsetGet($this->count() - 1);
    }

    /**
     * Get commit at index
     *
     * @param int $index the commit index
     *
     * @return Commit|null
     */
    public function index($index)
    {
        return $this->offsetGet($index);
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
        return isset($this->rangeCommits[$offset]);
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     *
     * @return Commit|null
     */
    public function offsetGet($offset)
    {
        return isset($this->rangeCommits[$offset]) ? $this->rangeCommits[$offset] : null;
    }

    /**
     * ArrayAccess interface
     *
     * @param int   $offset offset
     * @param mixed $value  value
     *
     * @throws \RuntimeException
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Can\'t set elements on logs');
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     *
     * @throws \RuntimeException
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Can\'t unset elements on logs');
    }

    /**
     * Countable interface
     *
     * @return int|void
     */
    public function count()
    {
        return count($this->rangeCommits);
    }

    /**
     * Iterator interface
     *
     * @return Commit|null
     */
    public function current()
    {
        return $this->offsetGet($this->position);
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
        return $this->offsetExists($this->position);
    }

    /**
     * Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Repository setter
     *
     * @param \GitElephant\Repository $repository the repository variable
     */
    public function setRepository($repository)
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
