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

use \GitElephant\Objects\Commit;
use \GitElephant\Repository;

/**
 * DiffTreeCommand
 *
 * diff-tree command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class DiffTreeCommand extends BaseCommand
{
    const DIFF_TREE_COMMAND = 'diff-tree';

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
     * get a diff of a root commit with the empty repository
     *
     * @param \GitElephant\Objects\Commit $commit the root commit object
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return string
     */
    public function rootDiff(Commit $commit)
    {
        if (!$commit->isRoot()) {
            throw new \InvalidArgumentException('rootDiff method accepts only root commits');
        }
        $this->clearAll();
        $this->addCommandName(static::DIFF_TREE_COMMAND);
        $this->addCommandArgument('--cc');
        $this->addCommandArgument('--root');
        $this->addCommandArgument('--dst-prefix=DST/');
        $this->addCommandArgument('--src-prefix=SRC/');
        $this->addCommandSubject($commit);

        return $this->getCommand();
    }
}
