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
 * show command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class ShowCommand extends BaseCommand
{
    const GIT_SHOW = 'show';

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
     * build the show command
     *
     * @param string|\GitElephant\Objects\Commit $ref the reference for the show command
     *
     * @throws \RuntimeException
     * @return string
     */
    public function showCommit($ref)
    {
        $this->clearAll();

        $this->addCommandName(self::GIT_SHOW);
        $this->addCommandArgument('-s');
        $this->addCommandArgument('--pretty=raw');
        $this->addCommandArgument('--no-color');
        $this->addCommandSubject($ref);

        return $this->getCommand();
    }
}
