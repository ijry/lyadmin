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

namespace GitElephant\Objects\Diff;

/**
 * A changed line in the DiffChunk
 *
 * @author Mathias Geat <mathias@ailoo.net>
 */
abstract class DiffChunkLineChanged extends DiffChunkLine
{
    /**
     * Line number
     *
     * @var int
     */
    protected $number;

    /**
     * Set line number
     *
     * @param int $number line number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get line number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get origin line number
     *
     * @return int
     */
    public function getOriginNumber()
    {
        return $this->getNumber();
    }

    /**
     * Get destination line number
     *
     * @return int
     */
    public function getDestNumber()
    {
        return $this->getNumber();
    }
}
