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

use \GitElephant\Objects\Branch;
use \GitElephant\Objects\Remote;
use \GitElephant\Repository;

/**
 * Class PushCommand
 */
class PushCommand extends BaseCommand
{
    const GIT_PUSH_COMMAND = 'push';

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
     * @param Remote|string $remote
     * @param Branch|string $branch
     *
     * @throws \RuntimeException
     * @return string
     */
    public function push($remote = 'origin', $branch = 'master', $args = null)
    {
        if ($remote instanceof Remote) {
            $remote = $remote->getName();
        }
        if ($branch instanceof Branch) {
            $branch = $branch->getName();
        }
        $this->clearAll();
        $this->addCommandName(self::GIT_PUSH_COMMAND);
        $this->addCommandSubject($remote);
        $this->addCommandSubject2($branch);

        if(!is_null($args)) {
            $this->addCommandArgument($args);
        }

        return $this->getCommand();
    }
}
