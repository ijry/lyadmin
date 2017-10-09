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

namespace GitElephant\Command;

use \GitElephant\Repository;

/**
 * SubCommandCommand
 *
 * A base class that can handle subcommand parameters ordering, which differs
 * for a general command
 *
 * @package GitElephant\Command
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */
class SubCommandCommand extends BaseCommand
{

    /**
     * Subjects to a subcommand name
     */
    private $orderedSubjects = array();

    /**
     * constructor
     *
     * @param \GitElephant\Repository $repo The repository object this command 
     *                                      will interact with
     */
    public function __construct(Repository $repo = null)
    {
        parent::__construct($repo);
    }

    /**
     * Clear all previous variables
     */
    public function clearAll()
    {
        parent::clearAll();
        $this->orderedSubjects = null;
    }

    protected function addCommandSubject($subject)
    {
        $this->orderedSubjects[] = $subject;
    }

    protected function getCommandSubjects()
    {
        return ($this->orderedSubjects) ? $this->orderedSubjects : array();
    }

    protected function extractArguments($args)
    {
        $orderArgs = array();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach ($arg as $value) {
                    if (!is_null($value)) {
                        $orderArgs[] = escapeshellarg($value);
                    }
                }
            } else {
                $orderArgs[] = escapeshellarg($arg);
            }
        }

        return implode(' ', $orderArgs);
    }

    /**
     * Get the sub command
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getCommand()
    {
        $command = $this->getCommandName();

        if (is_null($command)) {
            throw new \RuntimeException("commandName must be specified to build a subcommand");
        }

        $command .= ' ';
        $args = $this->getCommandArguments();
        if (count($args) > 0) {
            $command .= $this->extractArguments($args);
            $command .= ' ';
        }
        $subjects = $this->getCommandSubjects();
        if (count($subjects) > 0) {
            $command .= implode(' ', array_map('escapeshellarg', $subjects));
        }
        $command = preg_replace('/\\s{2,}/', ' ', $command);

        return trim($command);
    }
}
