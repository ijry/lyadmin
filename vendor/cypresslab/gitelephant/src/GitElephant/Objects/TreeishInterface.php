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

/**
 * TreeishInterface
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
interface TreeishInterface
{
    /**
     * get the unique sha for the treeish object
     *
     * @abstract
     */
    public function getSha();

    /**
     * toString magic method, should return the sha of the treeish
     *
     * @abstract
     */
    public function __toString();
}
