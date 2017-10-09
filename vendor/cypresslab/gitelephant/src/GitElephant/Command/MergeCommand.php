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

use \GitElephant\Objects\Branch;
use \GitElephant\Repository;

/**
 * Merge command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class MergeCommand extends BaseCommand
{
    const MERGE_COMMAND = 'merge';
    const MERGE_OPTION_FF_ONLY = '--ff-only';
    const MERGE_OPTION_NO_FF = '--no-ff';

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
     * Generate a merge command
     *
     * @param \GitElephant\Objects\Branch $with    the branch to merge
     * @param string                      $message a message for the merge commit, if merge is 3-way
     * @param array                       $options option flags for git merge
     *
     * @throws \RuntimeException
     * @return string
     */
    public function merge(Branch $with, $message = '', Array $options = array())
    {
        if (in_array(self::MERGE_OPTION_FF_ONLY, $options) && in_array(self::MERGE_OPTION_NO_FF, $options)) {
            throw new \Symfony\Component\Process\Exception\InvalidArgumentException("Invalid options: cannot use flags --ff-only and --no-ff together.");
        }
        $normalizedOptions = $this->normalizeOptions($options, $this->mergeCmdSwitchOptions());

        $this->clearAll();
        $this->addCommandName(static::MERGE_COMMAND);

        foreach ($normalizedOptions as $value) {
            $this->addCommandArgument($value);
        }

        if (!empty($message)) {
            $this->addCommandArgument('-m');
            $this->addCommandArgument($message);
        }

        $this->addCommandSubject($with->getFullRef());

        return $this->getCommand();
    }

    /**
     * Valid options for remote command that do not require an associated value
     *
     * @return array Associative array mapping all non-value options and their respective normalized option
     */
    public function mergeCmdSwitchOptions()
    {
        return array(
            self::MERGE_OPTION_FF_ONLY => self::MERGE_OPTION_FF_ONLY,
            self::MERGE_OPTION_NO_FF => self::MERGE_OPTION_NO_FF,
        );
    }
}
