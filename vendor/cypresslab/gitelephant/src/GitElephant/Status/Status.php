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

namespace GitElephant\Status;

use \GitElephant\Command\MainCommand;
use \GitElephant\Repository;
use \PhpCollection\Sequence;

/**
 * Class Status
 *
 * @package GitElephant\Status
 */
class Status
{
    /**
     * @var \GitElephant\Repository
     */
    private $repository;

    /**
     * @var array
     */
    protected $files;

    /**
     * @param Repository $repository
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    private function __construct(Repository $repository)
    {
        $this->files = array();
        $this->repository = $repository;
        $this->createFromCommand();
    }

    /**
     * @param Repository $repository
     *
     * @return \GitElephant\Status\Status
     */
    public static function get(Repository $repository)
    {
        return new static($repository);
    }

    /**
     * create from git command
     */
    private function createFromCommand()
    {
        $command = MainCommand::getInstance($this->repository)->status(true);
        $lines = $this->repository->getCaller()->execute($command)->getOutputLines(true);
        $this->parseOutputLines($lines);
    }

    /**
     * all files
     *
     * @return Sequence
     */
    public function all()
    {
        return new Sequence($this->files);
    }

    /**
     * untracked files
     *
     * @return Sequence
     */
    public function untracked()
    {
        return $this->filterByType(StatusFile::UNTRACKED);
    }

    /**
     * modified files
     *
     * @return Sequence
     */
    public function modified()
    {
        return $this->filterByType(StatusFile::MODIFIED);
    }

    /**
     * added files
     *
     * @return Sequence
     */
    public function added()
    {
        return $this->filterByType(StatusFile::ADDED);
    }

    /**
     * deleted files
     *
     * @return Sequence
     */
    public function deleted()
    {
        return $this->filterByType(StatusFile::DELETED);
    }

    /**
     * renamed files
     *
     * @return Sequence
     */
    public function renamed()
    {
        return $this->filterByType(StatusFile::RENAMED);
    }

    /**
     * copied files
     *
     * @return Sequence
     */
    public function copied()
    {
        return $this->filterByType(StatusFile::COPIED);
    }

    /**
     * create objects from command output
     * https://www.kernel.org/pub/software/scm/git/docs/git-status.html in the output section
     *
     *
     * @param array $lines
     */
    private function parseOutputLines($lines)
    {
        foreach ($lines as $line) {
            preg_match('/([MADRCU\? ])?([MADRCU\? ])?\ "?(\S+)"? ?( -> )?(\S+)?/', $line, $matches);
            $x = isset($matches[1]) ? $matches[1] : null;
            $y = isset($matches[2]) ? $matches[2] : null;
            $file = isset($matches[3]) ? $matches[3] : null;
            $renamedFile = isset($matches[4]) ? $matches[4] : null;
            $this->files[] = StatusFile::create($x, $y, $file, $renamedFile);
        }
    }

    /**
     * @param string $line
     *
     * @return mixed
     */
    protected function splitStatusLine($line)
    {
        preg_match('/([MADRCU\?])?([MADRCU\?])?\ "?(\S+)"? ?( -> )?(\S+)?/', $line, $matches);

        return $matches;
    }

    /**
     * filter files status in working tree and in index status
     *
     * @param string $type
     *
     * @return Sequence
     */
    protected function filterByType($type)
    {
        if (!$this->files) {
            return new Sequence();
        }

        return new Sequence(array_filter($this->files, function (StatusFile $statusFile) use ($type) {
            return $type === $statusFile->getWorkingTreeStatus() || $type === $statusFile->getIndexStatus();
        }));
    }
}
