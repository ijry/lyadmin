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

namespace GitElephant\Command\Remote;

use \GitElephant\Command\SubCommandCommand;
use \GitElephant\Repository;

/**
 * Class ShowRemoteCommand
 *
 * remote subcommand generator for show
 *
 * @package GitElephant\Objects
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */

class ShowSubCommand extends SubCommandCommand
{
    const GIT_REMOTE_SHOW = 'show';

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
     * build show sub command
     *
     * NOTE: for technical reasons $name is optional, however under normal
     * implementation it SHOULD be passed!
     *
     * @param string $name
     * @param bool   $queryRemotes Fetch new information from remotes
     *
     * @return ShowSubCommand
     */
    public function prepare($name = null, $queryRemotes = true)
    {
        $this->addCommandName(self::GIT_REMOTE_SHOW);
        /**
         *  only add subject if relevant,
         *  otherwise on repositories without a remote defined (ie, fresh
         *  init'd or mock) will likely trigger warning/error condition
         *
         */
        if ($name) {
            $this->addCommandSubject($name);
        }

        if (!$queryRemotes) {
            $this->addCommandArgument('-n');
        }

        return $this;
    }
}
