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

use \GitElephant\Objects\Object;
use \GitElephant\Repository;

/**
 * Class MvCommand
 *
 * @package GitElephant\Command
 */
class MvCommand extends BaseCommand
{
    const MV_COMMAND = 'mv';

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
     * @param string|Object $source source name
     * @param string        $target dest name
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return string
     */
    public function rename($source, $target)
    {
        if ($source instanceof Object) {
            if (!$source->isBlob()) {
                throw new \InvalidArgumentException("The given object is not a blob, it couldn't be renamed");
            }
            $sourceName = $source->getFullPath();
        } else {
            $sourceName = $source;
        }
        $this->clearAll();
        $this->addCommandName(self::MV_COMMAND);
        // Skip move or rename actions which would lead to an error condition
        $this->addCommandArgument('-k');
        $this->addCommandSubject($sourceName);
        $this->addCommandSubject2($target);

        return $this->getCommand();
    }
}
