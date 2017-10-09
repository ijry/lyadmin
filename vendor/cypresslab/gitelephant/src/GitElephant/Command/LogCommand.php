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

namespace GitElephant\Command;

use \GitElephant\Objects\Object;
use \GitElephant\Objects\Branch;
use \GitElephant\Objects\TreeishInterface;
use \GitElephant\Repository;

/**
 * Log command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 * @author Dhaval Patel <tech.dhaval@gmail.com>
 */
class LogCommand extends BaseCommand
{
    const GIT_LOG = 'log';

    /**
     * constructor
     *
     * @param \GitElephant\Repository $repo The repository object this command 
     *                                      will interact with
     */
    public function __construct(Repository $repo = null)
    {
        parent::__construct($repo);
    }

    /**
     * Build an object log command
     *
     * @param \GitElephant\Objects\Object             $obj    the Object to get the log for
     * @param \GitElephant\Objects\Branch|string|null $branch the branch to consider
     * @param int|null                                $limit  limit to n entries
     * @param int|null                                $offset skip n entries
     *
     * @throws \RuntimeException
     * @return string
     */
    public function showObjectLog(Object $obj, $branch = null, $limit = null, $offset = null)
    {
        $subject = null;
        if (null !== $branch) {
            if ($branch instanceof Branch) {
                $subject .= $branch->getName();
            } else {
                $subject .= (string) $branch;
            }
        }

        return $this->showLog($subject, $obj->getFullPath(), $limit, $offset);
    }

    /**
     * Build a generic log command
     *
     * @param \GitElephant\Objects\TreeishInterface|string $ref         the reference to build the log for
     * @param string|null                                  $path        the physical path to the tree relative to the
     *                                                                  repository root
     * @param int|null                                     $limit       limit to n entries
     * @param int|null                                     $offset      skip n entries
     * @param boolean|false                                $firstParent skip commits brought in to branch by a merge
     *
     * @throws \RuntimeException
     * @return string
     */
    public function showLog($ref, $path = null, $limit = null, $offset = null, $firstParent = false)
    {
        $this->clearAll();

        $this->addCommandName(self::GIT_LOG);
        $this->addCommandArgument('-s');
        $this->addCommandArgument('--pretty=raw');
        $this->addCommandArgument('--no-color');

        if (null !== $limit) {
            $limit = (int) $limit;
            $this->addCommandArgument('--max-count=' . $limit);
        }

        if (null !== $offset) {
            $offset = (int) $offset;
            $this->addCommandArgument('--skip=' . $offset);
        }

        if (true === $firstParent) {
            $this->addCommandArgument('--first-parent');
        }

        if ($ref instanceof TreeishInterface) {
            $ref = $ref->getSha();
        }

        if (null !== $path && !empty($path)) {
            $this->addPath($path);
        }

        $this->addCommandSubject($ref);

        return $this->getCommand();
    }
}
