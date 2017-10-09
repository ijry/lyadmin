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
 * Utilities class
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class Utilities
{
    /**
     * Replace / with the system directory separator
     *
     * @param string $path the original path
     *
     * @return mixed
     */
    public static function normalizeDirectorySeparator($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }

    /**
    * explode an array by lines that match a regular expression
    *
    * @param array  $array  the original array, should be a non-associative array
    * @param string $regexp the regular expression
    *
    * @return array an array of array pieces
    * @throws \InvalidArgumentException
    */
    public static function pregSplitArray($array, $regexp)
    {
        if (static::isAssociative($array)) {
            throw new \InvalidArgumentException('pregSplitArray only accepts non-associative arrays.');
        }
        $lineNumbers = array();
        $arrOut      = array();
        foreach ($array as $i => $line) {
            if (preg_match($regexp, $line)) {
                $lineNumbers[] = $i;
            }
        }

        foreach ($lineNumbers as $i => $lineNum) {
            if (isset($lineNumbers[$i + 1])) {
                $arrOut[] = array_slice($array, $lineNum, $lineNumbers[$i + 1] - $lineNum);
            } else {
                $arrOut[] = array_slice($array, $lineNum);
            }
        }

        return $arrOut;
    }

    /**
     * @param array  $array  a flat array
     * @param string $regexp a regular expression
     *
     * @return array
     */
    public static function pregSplitFlatArray($array, $regexp)
    {
        $index = 0;
        $slices = array();
        $slice = array();
        foreach ($array as $val) {
            if (preg_match($regexp, $val) && !empty($slice)) {
                $slices[$index] = $slice;
                ++$index;
                $slice = array();
            }
            $slice[] = $val;
        }
        if (!empty($slice)) {
            $slices[$index] = $slice;
        }

        return $slices;
    }

    /**
     * Tell if an array is associative
     *
     * @param array $arr an array
     *
     * @return bool
     */
    public static function isAssociative($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
