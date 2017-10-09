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

use \GitElephant\Objects\TreeishInterface;
use \GitElephant\Repository;

/**
 * Diff command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class DiffCommand extends BaseCommand
{
    const DIFF_COMMAND = 'diff';

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
     * build a diff command
     *
     * @param TreeishInterface      $of   the reference to diff
     * @param TreeishInterface|null $with the source reference to diff with $of, if not specified is the current HEAD
     * @param null                  $path the path to diff, if not specified the full repository
     *
     * @throws \RuntimeException
     * @return string
     */
    public function diff($of, $with = null, $path = null)
    {
        $this->clearAll();
        $this->addCommandName(self::DIFF_COMMAND);
        // Instead of the first handful of characters, show the full pre- and post-image blob object names on the
        // "index" line when generating patch format output
        $this->addCommandArgument('--full-index');
        $this->addCommandArgument('--no-color');
        // Disallow external diff drivers
        $this->addCommandArgument('--no-ext-diff');
        // Detect renames
        $this->addCommandArgument('-M');
        $this->addCommandArgument('--dst-prefix=DST/');
        $this->addCommandArgument('--src-prefix=SRC/');

        $subject = '';

        if (is_null($with)) {
            $subject .= $of.'^..'.$of;
        } else {
            $subject .= $with.'..'.$of;
        }

        if (! is_null($path)) {
            if (!is_string($path)) {
                /** @var Object $path */
                $path = $path->getPath();
            }
            $this->addPath($path);
        }

        $this->addCommandSubject($subject);

        return $this->getCommand();
    }
}
