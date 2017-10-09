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

namespace GitElephant;

/**
 * Git binary
 * It contains the reference to the system git binary
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class GitBinary
{
    /**
     * the path to the repository
     *
     * @var string $path
     */
    private $path;

    /**
     * Class constructor
     *
     * @param null $path the physical path to the git binary
     */
    public function __construct($path = null)
    {
        if (is_null($path)) {
            // unix only!
            $path = exec('which git');
        }
        $this->setPath($path);
    }

    /**
     * path getter
     * returns the path of the binary
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * path setter
     *
     * @param string $path the path to the system git binary
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
