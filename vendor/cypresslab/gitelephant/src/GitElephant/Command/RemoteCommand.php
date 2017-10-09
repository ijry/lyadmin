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

use \GitElephant\Command\Remote\AddSubCommand;
use \GitElephant\Command\Remote\ShowSubCommand;
use \GitElephant\Repository;

/**
 * Class RemoteCommand
 *
 * remote command generator
 *
 * @package GitElephant\Objects
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */
class RemoteCommand extends BaseCommand
{
    const GIT_REMOTE = 'remote';
    const GIT_REMOTE_OPTION_VERBOSE = '--verbose';
    const GIT_REMOTE_OPTION_VERBOSE_SHORT = '-v';

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
     * Build the remote command
     *
     * NOTE: git-remote is most useful when using its subcommands, therefore
     * in practice you will likely pass a SubCommandCommand object. This
     * class provide "convenience" methods that do this for you.
     *
     * @param \GitElephant\Command\SubCommandCommand $subcommand A subcommand object
     * @param array                                  $options    Options for the main git-remote command
     *
     * @throws \RuntimeException
     * @return string Command string to pass to caller
     */
    public function remote(SubCommandCommand $subcommand = null, Array $options = array())
    {
        $normalizedOptions = $this->normalizeOptions($options, $this->remoteCmdSwitchOptions());

        $this->clearAll();

        $this->addCommandName(self::GIT_REMOTE);

        foreach ($normalizedOptions as $value) {
            $this->addCommandArgument($value);
        }

        if ($subcommand) {
            $this->addCommandSubject($subcommand);
        }

        return $this->getCommand();
    }

    /**
     * Valid options for remote command that do not require an associated value
     *
     * @return array Associative array mapping all non-value options and their respective normalized option
     */
    public function remoteCmdSwitchOptions()
    {
        return array(
            self::GIT_REMOTE_OPTION_VERBOSE => self::GIT_REMOTE_OPTION_VERBOSE,
            self::GIT_REMOTE_OPTION_VERBOSE_SHORT => self::GIT_REMOTE_OPTION_VERBOSE,
        );
    }

    /**
     * git-remote --verbose command
     *
     * @throws \RuntimeException
     * @return string
     */
    public function verbose()
    {
        return $this->remote(null, array(self::GIT_REMOTE_OPTION_VERBOSE));
    }

    /**
     * git-remote show [name] command
     *
     * NOTE: for technical reasons $name is optional, however under normal
     * implementation it SHOULD be passed!
     *
     * @param string $name
     * @param bool   $queryRemotes
     *
     * @throws \RuntimeException
     * @return string
     */
    public function show($name = null, $queryRemotes = true)
    {
        $subcmd = new ShowSubCommand();
        $subcmd->prepare($name, $queryRemotes);

        return $this->remote($subcmd);
    }

    /**
     * git-remote add [options] <name> <url>
     *
     * @param string $name    remote name
     * @param string $url     URL of remote
     * @param array  $options options for the add subcommand
     *
     * @throws \RuntimeException
     * @return string
     */
    public function add($name, $url, $options = array())
    {
        $subcmd = new AddSubCommand();
        $subcmd->prepare($name, $url, $options);

        return $this->remote($subcmd);
    }
}
