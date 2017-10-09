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
use \GitElephant\Objects\TreeishInterface;
use \GitElephant\Repository;

/**
 * cat-file command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class CatFileCommand extends BaseCommand
{
    const GIT_CAT_FILE = 'cat-file';

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
     * command to show content of a Object at a given Treeish point
     *
     * @param \GitElephant\Objects\Object                  $object  a Object instance
     * @param \GitElephant\Objects\TreeishInterface|string $treeish an object with TreeishInterface interface
     *
     * @throws \RuntimeException
     * @return string
     */
    public function content(Object $object, $treeish)
    {
        $this->clearAll();
        if ($treeish instanceof TreeishInterface) {
            $sha = $treeish->getSha();
        } else {
            $sha = $treeish;
        }
        $this->addCommandName(static::GIT_CAT_FILE);
        // pretty format
        $this->addCommandArgument('-p');
        $this->addCommandSubject($sha . ':' . $object->getFullPath());

        return $this->getCommand();
    }

    /**
     * output an object content given it's sha
     *
     * @param string $sha
     *
     * @throws \RuntimeException
     * @return string
     */
    public function contentBySha($sha)
    {
        $this->clearAll();
        $this->addCommandName(static::GIT_CAT_FILE);
        $this->addCommandArgument('-p');
        $this->addCommandSubject($sha);

        return $this->getCommand();
    }
}
