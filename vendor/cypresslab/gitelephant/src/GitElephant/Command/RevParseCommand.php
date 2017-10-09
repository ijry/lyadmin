<?php
/**
 * GitElephant - An abstraction layer for git written in PHP
 * Copyright (C) 2014  John Schlick John_Schlick@hotmail.com
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
 * Class RevParseCommand
 */
class RevParseCommand extends BaseCommand
{
    const GIT_REV_PARSE_COMMAND = 'rev-parse';

    const OPTION_ALL = '--all';
    const OPTION_KEEP_DASHDASH = '--keep-dashdash';
    const OPTION_STOP_AT_NON_OPTION = '--stop-at-non-option';
    const OPTION_SQ_QUOTE = '--sq-quote';
    const OPTION_REVS_ONLY = '--revs-only';
    const OPTION_NO_REVS = '--no-revs';
    const OPTION_FLAGS = '--flags';
    const OPTION_NO_FLAGS = '--no-flags';
    const OPTION_DEFAULT = '--default';
    const OPTION_VERIFY = '--verify';
    const OPTION_QUIET = '--quiet';
    const OPTION_SQ = '--sq';
    const OPTION_NOT = '--not';
    const OPTION_SYMBOLIC = '--symbolic';
    const OPTION_SYMBOLIC_FULL_NAME = '--symbolic-full-name';
    const OPTION_ABBREV_REF = '--abbrev-ref';
    const OPTION_DISAMBIGUATE = '--disambiguate';
    const OPTION_BRANCHES = '--branches';
    const OPTION_TAGS = '--tags';
    const OPTION_REMOTES = '--remotes';
    const OPTION_GLOB = '--glob';
    const OPTION_SHOW_TOPLEVEL = '--show-toplevel';
    const OPTION_SHOW_PREFIX = '--show-prefix';
    const OPTION_SHOW_CDUP = '--show-cdup';
    const OPTION_GIT_DIR = '--git-dir';
    const OPTION_IS_INSIDE_GIT_DIR = '--is-inside-git-dir';
    const OPTION_IS_INSIDE_WORK_TREE = '--is-inside-work-tree';
    const OPTION_IS_BARE_REPOSIORY = '--is-bare-repository';
    const OPTION_LCOAL_ENV_VARS = '--local-env-vars';
    const OPTION_SHORT = '--short';
    const OPTION_SINCE = '--since';
    const OPTION_AFTER = '--after';
    const OPTION_UNTIL = '--until';
    const OPTION_BEFORE = '--before';
    const OPTION_RESOLVE_GIT_DIR = '--resolve-git-dir';

    const TAG_HEAD = "HEAD";

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
     * @param array $options
     * @param Branch|string $arg
     *
     * @throws \RuntimeException
     * @return string
     */
    public function revParse($arg = null, Array $options = array())
    {
        $this->clearAll();
        $this->addCommandName(self::GIT_REV_PARSE_COMMAND);
        // if there are options add them.
        if (! is_null($options)) {
            foreach ($options as $option) {
                $this->addCommandArgument($option);
            }
        }
        if (! is_null($arg)) {
            $this->addCommandSubject2($arg);
        }

        return $this->getCommand();
    }
}
