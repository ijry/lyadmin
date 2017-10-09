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
 * A single portion of a file changed in a diff
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class DiffChunk implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * the cursor position
     *
     * @var int
     */
    private $position;

    /**
     * diff start line from original file
     *
     * @var int
     */
    private $originStartLine;

    /**
     * diff end line from original file
     *
     * @var int
     */
    private $originEndLine;

    /**
     * diff start line from destination file
     *
     * @var int
     */
    private $destStartLine;

    /**
     * diff end line from destination file
     *
     * @var int
     */
    private $destEndLine;

    /**
     * hunk header line
     *
     * @var string
     */
    private $headerLine;

    /**
     * array of lines
     *
     * @var array
     */
    private $lines;

    /**
     * Class constructor
     *
     * @param array $lines output lines from git binary
     *
     * @throws \Exception
     */
    public function __construct($lines)
    {
        $this->position = 0;

        $this->getLinesNumbers($lines[0]);
        $this->parseLines(array_slice($lines, 1));
    }

    /**
     * Parse lines
     *
     * @param array $lines output lines
     *
     * @throws \Exception
     */
    private function parseLines($lines)
    {
        $originUnchanged = $this->originStartLine;
        $destUnchanged = $this->destStartLine;

        $deleted = $this->originStartLine;
        $new = $this->destStartLine;
        foreach ($lines as $line) {
            if (preg_match('/^\+(.*)/', $line)) {
                $this->lines[] = new DiffChunkLineAdded($new++, preg_replace('/\+(.*)/', ' $1', $line));
                $destUnchanged++;
            } else {
                if (preg_match('/^-(.*)/', $line)) {
                    $this->lines[] = new DiffChunkLineDeleted($deleted++, preg_replace('/-(.*)/', ' $1', $line));
                    $originUnchanged++;
                } else {
                    if (preg_match('/^ (.*)/', $line) || $line == '') {
                        $this->lines[] = new DiffChunkLineUnchanged($originUnchanged++, $destUnchanged++, $line);
                        $deleted++;
                        $new++;
                    } else {
                        if (!preg_match('/\\ No newline at end of file/', $line)) {
                            throw new \Exception(sprintf('GitElephant was unable to parse the line %s', $line));
                        }
                    }
                }
            }
        }
    }

    /**
     * Get line numbers
     *
     * @param string $line a single line
     */
    private function getLinesNumbers($line)
    {
        $matches = array();
        preg_match('/@@ -(.*) \+(.*) @@?(.*)/', $line, $matches);
        if (!strpos($matches[1], ',')) {
            // one line
            $this->originStartLine = $matches[1];
            $this->originEndLine   = $matches[1];
        } else {
            list($this->originStartLine, $this->originEndLine) = explode(',', $matches[1]);
        }
        if (!strpos($matches[2], ',')) {
            // one line
            $this->destStartLine = $matches[2];
            $this->destEndLine   = $matches[2];
        } else {
            list($this->destStartLine, $this->destEndLine) = explode(',', $matches[2]);
        }
    }

    /**
     * destStartLine getter
     *
     * @return int
     */
    public function getDestStartLine()
    {
        return $this->destStartLine;
    }

    /**
     * destEndLine getter
     *
     * @return int
     */
    public function getDestEndLine()
    {
        return $this->destEndLine;
    }

    /**
     * originStartLine getter
     *
     * @return int
     */
    public function getOriginStartLine()
    {
        return $this->originStartLine;
    }

    /**
     * originEndLine getter
     *
     * @return int
     */
    public function getOriginEndLine()
    {
        return $this->originEndLine;
    }

    /**
    * Get hunk header line
    *
    * @return string
    */
    public function getHeaderLine()
    {
        if (null === $this->headerLine) {
            $line  = '@@';
            $line .= ' -' . $this->getOriginStartLine() . ',' . $this->getOriginEndLine();
            $line .= ' +' . $this->getDestStartLine() . ',' . $this->getDestEndLine();
            $line .= ' @@';

            $this->headerLine = $line;
        }

        return $this->headerLine;
    }

    /**
     * Get Lines
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->lines[$offset]);
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return isset($this->lines[$offset]) ? $this->lines[$offset] : null;
    }

    /**
     * ArrayAccess interface
     *
     * @param int   $offset offset
     * @param mixed $value  value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->lines[] = $value;
        } else {
            $this->lines[$offset] = $value;
        }
    }

    /**
     * ArrayAccess interface
     *
     * @param int $offset offset
     */
    public function offsetUnset($offset)
    {
        unset($this->lines[$offset]);
    }

    /**
     * Countable interface
     *
     * @return int|void
     */
    public function count()
    {
        return count($this->lines);
    }

    /**
     * Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return $this->lines[$this->position];
    }

    /**
     * Iterator interface
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Iterator interface
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Iterator interface
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->lines[$this->position]);
    }

    /**
     * Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
