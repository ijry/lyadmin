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
use \GitElephant\Command\TagCommand;
use \GitElephant\Command\RevListCommand;

/**
 * An object representing a git tag
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class Tag extends Object
{
    /**
     * tag name
     *
     * @var string
     */
    private $name;

    /**
     * full reference
     *
     * @var string
     */
    private $fullRef;

    /**
     * sha
     *
     * @var string
     */
    private $sha;

    /**
     * Creates a new tag on the repository and returns it
     *
     * @param \GitElephant\Repository $repository repository instance
     * @param string                  $name       branch name
     * @param string                  $startPoint branch to start from
     * @param string                  $message    tag message
     *
     * @throws \RuntimeException
     * @return \GitElephant\Objects\Branch
     */
    public static function create(Repository $repository, $name, $startPoint = null, $message = null)
    {
        $repository->getCaller()->execute(TagCommand::getInstance($repository)->create($name, $startPoint, $message));

        return $repository->getTag($name);
    }

    /**
     * static generator to generate a single commit from output of command.show service
     *
     * @param \GitElephant\Repository $repository  repository
     * @param array                   $outputLines output lines
     * @param string                  $name        name
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return Commit
     */
    public static function createFromOutputLines(Repository $repository, $outputLines, $name)
    {
        $tag = new self($repository, $name);
        $tag->parseOutputLines($outputLines);

        return $tag;
    }

    /**
     * Class constructor
     *
     * @param \GitElephant\Repository $repository repository instance
     * @param string                  $name       name
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @internal param string $line a single tag line from the git binary
     */
    public function __construct(Repository $repository, $name)
    {
        $this->repository = $repository;
        $this->name    = $name;
        $this->fullRef = 'refs/tags/' . $this->name;
        $this->createFromCommand();
    }

    /**
     * factory method
     *
     * @param \GitElephant\Repository $repository repository instance
     * @param string                  $name       name
     *
     * @return \GitElephant\Objects\Tag
     */
    public static function pick(Repository $repository, $name)
    {
        return new self($repository, $name);
    }

    /**
     * deletes the tag
     */
    public function delete()
    {
        $this->repository
            ->getCaller()
            ->execute(TagCommand::getInstance($this->getRepository())->delete($this));
    }

    /**
     * get the commit properties from command
     *
     * @see ShowCommand::commitInfo
     */
    private function createFromCommand()
    {
        $command = TagCommand::getInstance($this->getRepository())->listTags();
        $outputLines = $this->getCaller()->execute($command, true, $this->getRepository()->getPath())->getOutputLines();
        $this->parseOutputLines($outputLines);
    }

    /**
     * parse the output of a git command showing a commit
     *
     * @param array $outputLines output lines
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return void
     */
    private function parseOutputLines($outputLines)
    {
        $found = false;
        foreach ($outputLines as $tagString) {
            if ($tagString != '') {
                if ($this->name === trim($tagString)) {
                    $lines = $this->getCaller()
                        ->execute(RevListCommand::getInstance($this->getRepository())->getTagCommit($this))
                        ->getOutputLines();
                    $this->setSha($lines[0]);
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) {
            throw new \InvalidArgumentException(sprintf('the tag %s doesn\'t exists', $this->name));
        }
    }

    /**
     * toString magic method
     *
     * @return string the sha
     */
    public function __toString()
    {
        return $this->getSha();
    }

    /**
     * @return \GitElephant\Command\Caller\Caller
     */
    private function getCaller()
    {
        return $this->getRepository()->getCaller();
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
     * fullRef getter
     *
     * @return string
     */
    public function getFullRef()
    {
        return $this->fullRef;
    }

    /**
     * sha setter
     *
     * @param string $sha sha
     */
    public function setSha($sha)
    {
        $this->sha = $sha;
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
}
