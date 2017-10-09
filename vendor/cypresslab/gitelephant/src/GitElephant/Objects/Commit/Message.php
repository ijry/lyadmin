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

namespace GitElephant\Objects\Commit;

/**
 * Represents a commit message
 *
 * @author Mathias Geat <mathias@ailoo.net>
 */
class Message
{
    /**
     * the message
     *
     * @var array|string
     */
    private $message;

    /**
     * Class constructor
     *
     * @param array|string $message Message lines
     */
    public function __construct($message)
    {
        if (is_array($message)) {
            $this->message = $message;
        } else {
            $this->message = array();
            $this->message = (string) $message;
        }
    }

    /**
     * Short message equals first message line
     *
     * @return string|null
     */
    public function getShortMessage()
    {
        return $this->toString();
    }

    /**
     * Full commit message
     *
     * @return string|null
     */
    public function getFullMessage()
    {
        return $this->toString(true);
    }

    /**
     * Return message string
     *
     * @param bool $full get the full message
     *
     * @return string|null
     */
    public function toString($full = false)
    {
        if (count($this->message) == 0) {
            return null;
        }

        if ($full) {
            return implode(PHP_EOL, $this->message);
        } else {
            return $this->message[0];
        }
    }

    /**
     * String representation equals short message
     *
     * @return string|null
     */
    public function __toString()
    {
        return $this->toString();
    }
}
