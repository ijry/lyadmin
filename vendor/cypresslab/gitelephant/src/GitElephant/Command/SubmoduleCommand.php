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
 * Submodule command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class SubmoduleCommand extends BaseCommand
{
    const SUBMODULE_COMMAND          = 'submodule';
    const SUBMODULE_ADD_COMMAND      = 'add';
    const SUBMODULE_INIT_COMMAND     = 'init';
    const SUBMODULE_UPDATE_COMMAND   = 'update';
    const SUBMODULE_OPTION_FORCE     = '--force';
    const SUBMODULE_OPTION_INIT      = '--init';
    const SUBMODULE_OPTION_RECURSIVE = '--recursive';

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
     * add a submodule
     *
     * @param string $gitUrl git url of the submodule
     * @param string $path   path to register the submodule to
     *
     * @throws \RuntimeException
     * @return string
     */
    public function add($gitUrl, $path = null)
    {
        $this->clearAll();
        $this->addCommandName(sprintf('%s %s', self::SUBMODULE_COMMAND, self::SUBMODULE_ADD_COMMAND));
        $this->addCommandArgument($gitUrl);
        if (null !== $path) {
            $this->addCommandSubject($path);
        }

        return $this->getCommand();
    }

    /**
     * initialize a repository's submodules
     *
     * @param  string $path init only submodules at the specified path
     *
     * @return string
     */
    public function init($path = null)
    {
        $this->clearAll();
        $this->addCommandName(sprintf('%s %s', self::SUBMODULE_COMMAND, self::SUBMODULE_INIT_COMMAND));
        if (null !== $path) {
            $this->addPath($path);
        }

        return $this->getCommand();
    }

    /**
     * Lists submodules
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function listSubmodules()
    {
        $this->clearAll();
        $this->addCommandName(self::SUBMODULE_COMMAND);

        return $this->getCommand();
    }

    /**
     * Lists submodules
     *
     * @deprecated This method uses an unconventional name but is being left in
     *             place to remain compatible with existing code relying on it.
     *             New code should be written to use listSubmodules().
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function lists()
    {
        return $this->listSubmodules();
    }

    /**
     * update a repository's submodules
     *
     * @param  bool   $recursive update recursively
     * @param  bool   $init      init before update
     * @param  bool   $force     force the checkout as part of update
     * @param  string $path      update only a specific submodule path
     *
     * @return string
     */
    public function update($recursive = false, $init = false, $force = false, $path = null)
    {
        $this->clearAll();
        $this->addCommandName(sprintf('%s %s', self::SUBMODULE_COMMAND, self::SUBMODULE_UPDATE_COMMAND));
        if ($recursive === true) {
            $this->addCommandArgument(self::SUBMODULE_OPTION_RECURSIVE);
        }
        if ($init === true) {
            $this->addCommandArgument(self::SUBMODULE_OPTION_INIT);
        }
        if ($force === true) {
            $this->addCommandArgument(self::SUBMODULE_OPTION_FORCE);
        }
        if ($path !== null) {
            $this->addPath($path);
        }

        return $this->getCommand();
    }
}
