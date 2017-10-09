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

namespace GitElephant\Command\Caller;

/**
 * interface for the git command caller
 */
interface CallerInterface
{
    /**
     * execute a command
     *
     * @param string      $cmd the command
     * @param bool        $git prepend git to the command
     * @param null|string $cwd directory where the command should be executed
     *
     * @return CallerInterface
     */
    public function execute($cmd, $git = true, $cwd = null);

    /**
     * after calling execute this method should return the output
     *
     * @param bool $stripBlankLines strips the blank lines
     *
     * @return array
     */
    public function getOutputLines($stripBlankLines = false);
}
