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

use \GitElephant\Objects\Tag;
use \GitElephant\Repository;

/**
 * Tag command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class TagCommand extends BaseCommand
{
    const TAG_COMMAND = 'tag';

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
     * Create a new tag
     *
     * @param string      $name       The new tag name
     * @param string|null $startPoint the new tag start point.
     * @param null        $message    the tag message
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function create($name, $startPoint = null, $message = null)
    {
        $this->clearAll();
        $this->addCommandName(self::TAG_COMMAND);
        if (null !== $message) {
            $this->addCommandArgument('-m');
            $this->addCommandArgument($message);
        }
        if (null !== $startPoint) {
            $this->addCommandArgument($name);
            $this->addCommandSubject($startPoint);
        } else {
            $this->addCommandSubject($name);
        }

        return $this->getCommand();
    }

    /**
     * Lists tags
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function listTags()
    {
        $this->clearAll();
        $this->addCommandName(self::TAG_COMMAND);

        return $this->getCommand();
    }

    /**
     * Lists tags
     *
     * @deprecated This method uses an unconventional name but is being left in
     *             place to remain compatible with existing code relying on it.
     *             New code should be written to use listTags().
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function lists()
    {
        return $this->listTags();
    }

    /**
     * Delete a tag
     *
     * @param string|Tag $tag The name of tag, or the Tag instance to delete
     *
     * @throws \RuntimeException
     * @return string the command
     */
    public function delete($tag)
    {
        $this->clearAll();

        $name = $tag;
        if ($tag instanceof Tag) {
            $name = $tag->getName();
        }

        $this->addCommandName(self::TAG_COMMAND);
        $this->addCommandArgument('-d');
        $this->addCommandSubject($name);

        return $this->getCommand();
    }
}
