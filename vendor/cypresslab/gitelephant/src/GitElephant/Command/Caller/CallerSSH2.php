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
 * Caller via ssh2 PECL extension
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class CallerSSH2 implements CallerInterface
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var string
     */
    private $gitPath;

    /**
     * the output lines of the command
     *
     * @var array
     */
    private $outputLines = array();

    /**
     * @param resource $resource
     * @param string   $gitPath path of the git executable on the remote host
     *
     * @internal param string $host remote host
     * @internal param int $port remote port
     */
    public function __construct($resource, $gitPath = '/usr/bin/git')
    {
        $this->resource = $resource;
        $this->gitPath = $gitPath;
    }

    /**
     * execute a command
     *
     * @param string      $cmd the command
     * @param bool        $git prepend git to the command
     * @param null|string $cwd directory where the command should be executed
     *
     * @return CallerInterface
     */
    public function execute($cmd, $git = true, $cwd = null)
    {
        if ($git) {
            $cmd = $this->gitPath . ' ' . $cmd;
        }
        $stream = ssh2_exec($this->resource, $cmd);
        stream_set_blocking($stream, 1);
        $data = stream_get_contents($stream);
        fclose($stream);
        // rtrim values
        $values = array_map('rtrim', explode(PHP_EOL, $data));
        $this->outputLines = $values;

        return $this;
    }

    /**
     * after calling execute this method should return the output
     *
     * @param bool $stripBlankLines strips the blank lines
     *
     * @return array
     */
    public function getOutputLines($stripBlankLines = false)
    {
        if ($stripBlankLines) {
            $output = array();
            foreach ($this->outputLines as $line) {
                if ('' !== $line) {
                    $output[] = $line;
                }
            }

            return $output;
        }

        return $this->outputLines;
    }
}
