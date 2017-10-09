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

use \GitElephant\Repository;

/**
 * Branch command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class BranchCommand extends BaseCommand
{
    const BRANCH_COMMAND = 'branch';

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
     * Locate branches that contain a reference
     *
     * @param string $reference reference
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function contains($reference)
    {
        $this->clearAll();
        $this->addCommandName(self::BRANCH_COMMAND);
        $this->addCommandArgument('--contains');
        $this->addCommandSubject($reference);

        return $this->getCommand();
    }

    /**
     * Create a new branch
     *
     * @param string      $name       The new branch name
     * @param string|null $startPoint the new branch start point.
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function create($name, $startPoint = null)
    {
        $this->clearAll();
        $this->addCommandName(self::BRANCH_COMMAND);
        $this->addCommandSubject($name);
        if (null !== $startPoint) {
            $this->addCommandSubject2($startPoint);
        }

        return $this->getCommand();
    }

    /**
     * Lists branches
     *
     * @param bool $all    lists all remotes
     * @param bool $simple list only branch names
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function listBranches($all = false, $simple = false)
    {
        $this->clearAll();
        $this->addCommandName(self::BRANCH_COMMAND);
        if (!$simple) {
            $this->addCommandArgument('-v');
        }
        $this->addCommandArgument('--no-color');
        $this->addCommandArgument('--no-abbrev');
        if ($all) {
            $this->addCommandArgument('-a');
        }

        return $this->getCommand();
    }

    /**
     * Lists branches
     *
     * @deprecated This method uses an unconventional name but is being left in
     *             place to remain compatible with existing code relying on it.
     *             New code should be written to use listBranches().
     *
     * @param bool $all    lists all remotes
     * @param bool $simple list only branch names
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function lists($all = false, $simple = false)
    {
        return $this->listBranches($all, $simple);
    }

    /**
     * get info about a single branch
     *
     * @param string $name    The branch name
     * @param bool   $all     lists all remotes
     * @param bool   $simple  list only branch names
     * @param bool   $verbose verbose, show also the upstream branch
     *
     * @throws \RuntimeException
     * @return string
     */
    public function singleInfo($name, $all = false, $simple = false, $verbose = false)
    {
        $this->clearAll();
        $this->addCommandName(self::BRANCH_COMMAND);
        if (!$simple) {
            $this->addCommandArgument('-v');
        }
        $this->addCommandArgument('--list');
        $this->addCommandArgument('--no-color');
        $this->addCommandArgument('--no-abbrev');
        if ($all) {
            $this->addCommandArgument('-a');
        }
        if ($verbose) {
            $this->addCommandArgument('-vv');
        }
        $this->addCommandSubject($name);

        return $this->getCommand();
    }

    /**
     * Delete a branch by its name
     *
     * @param string $name  The branch to delete
     * @param bool   $force Force the delete
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function delete($name, $force = false)
    {
        $arg = ($force === true) ? '-D' : '-d';
        $this->clearAll();
        $this->addCommandName(self::BRANCH_COMMAND);
        $this->addCommandArgument($arg);
        $this->addCommandSubject($name);

        return $this->getCommand();
    }
}
