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

namespace GitElephant\Status;

/**
 * Class StatusFile
 *
 * @package GitElephant\Status
 */
class StatusFile
{
    const UNTRACKED = '?';
    const IGNORED = '!';
    const UNMODIFIED = '';
    const MODIFIED = 'M';
    const ADDED = 'A';
    const DELETED = 'D';
    const RENAMED = 'R';
    const COPIED = 'C';
    const UPDATED_BUT_UNMERGED = 'U';

    /**
     * @var string
     */
    private $x;

    /**
     * @var string
     */
    private $y;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $renamed;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $x       X section of the status --porcelain output
     * @param string $y       Y section of the status --porcelain output
     * @param string $name    file name
     * @param string $renamed new file name (if renamed)
     */
    private function __construct($x, $y, $name, $renamed)
    {
        $this->x = ' ' === $x ? null : $x;
        $this->y = ' ' === $y ? null : $y;
        $this->name = $name;
        $this->renamed = $renamed;
        $this->calculateDescription();
    }

    /**
     * @param string $x       X section of the status --porcelain output
     * @param string $y       Y section of the status --porcelain output
     * @param string $name    file name
     * @param string $renamed new file name (if renamed)
     *
     * @return StatusFile
     */
    public static function create($x, $y, $name, $renamed)
    {
        return new self($x, $y, $name, $renamed);
    }

    /**
     * @return bool
     */
    public function isRenamed()
    {
        return $this->renamed !== null;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the status of the index
     *
     * @return string
     */
    public function getIndexStatus()
    {
        return $this->x;
    }

    /**
     * Get the status of the working tree
     *
     * @return string
     */
    public function getWorkingTreeStatus()
    {
        return $this->y;
    }

    /**
     * description of the status
     *
     * @return string
     */
    public function calculateDescription()
    {
        $status = $this->x.$this->y;
        $matching = array(
            '/ [MD]/' => 'not updated',
            '/M[MD]/' => 'updated in index',
            '/A[MD]/' => 'added to index',
            '/D[M]/' => 'deleted from index',
            '/R[MD]/' => 'renamed in index',
            '/C[MD]/' => 'copied in index',
            '/[MARC] /' => 'index and work tree matches',
            '/[MARC]M/' => 'work tree changed since index',
            '/[MARC]D/' => 'deleted in work tree',
            '/DD/' => 'unmerged, both deleted',
            '/AU/' => 'unmerged, added by us',
            '/UD/' => 'unmerged, deleted by them',
            '/UA/' => 'unmerged, added by them',
            '/DU/' => 'unmerged, deleted by us',
            '/AA/' => 'unmerged, both added',
            '/UU/' => 'unmerged, both modified',
            '/\?\?/' => 'untracked',
            '/!!/' => 'ignored',
        );
        $out = array();
        foreach ($matching as $pattern => $label) {
            if (preg_match($pattern, $status)) {
                $out[] = $label;
            }
        }

        $this->description = implode(', ', $out);
    }

    /**
     * Set Description
     *
     * @param string $description the description variable
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Type
     *
     * @param string $type the type variable
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
