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
 * DiffChunkLine unchanged
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class DiffChunkLineUnchanged extends DiffChunkLine
{
    /**
     * Origin line number
     *
     * @var int
     */
    protected $originNumber;

    /**
     * Destination line number
     *
     * @var int
     */
    protected $destNumber;

    /**
     * Class constructor
     *
     * @param int    $originNumber      original line number
     * @param int    $destinationNumber destination line number
     * @param string $content           line content
     *
     * @internal param int $number line number
     */
    public function __construct($originNumber, $destinationNumber, $content)
    {
        $this->setOriginNumber($originNumber);
        $this->setDestNumber($destinationNumber);
        $this->setContent($content);
        $this->setType(self::UNCHANGED);
    }

    /**
     * Set origin line number
     *
     * @param int $number line number
     */
    public function setOriginNumber($number)
    {
        $this->originNumber = $number;
    }

    /**
     * Get origin line number
     *
     * @return int
     */
    public function getOriginNumber()
    {
        return $this->originNumber;
    }

    /**
     * Set destination line number
     *
     * @param int $number line number
     */
    public function setDestNumber($number)
    {
        $this->destNumber = $number;
    }

    /**
     * Get destination line number
     *
     * @return int
     */
    public function getDestNumber()
    {
        return $this->destNumber;
    }
}
