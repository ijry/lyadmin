<?php
/**
 * This file is part of the GitElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package GitElephant\Command
 *
 * Just for fun...
 */

namespace GitElephant\Command;

use \GitElephant\Objects\TreeishInterface;
use \GitElephant\Repository;

/**
 * Log Range command generator
 *
 * @author Matteo Giachino <matteog@gmail.com>
 * @author John Cartwright <jcartdev@gmail.com>
 * @author Dhaval Patel <tech.dhaval@gmail.com>
 */
class LogRangeCommand extends BaseCommand
{
    const GIT_LOG = 'log';

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
     * Build a generic log command
     *
     * @param \GitElephant\Objects\TreeishInterface|string $refStart    the reference range start to build the log for
     * @param \GitElephant\Objects\TreeishInterface|string $refEnd      the reference range end to build the log for
     * @param string|null                                  $path        the physical path to the tree relative
     *                                                                  to the repository root
     * @param int|null                                     $limit       limit to n entries
     * @param int|null                                     $offset      skip n entries
     * @param boolean|false                                $firstParent skip commits brought in to branch by a merge
     *
     * @throws \RuntimeException
     * @return string
     */
    public function showLog($refStart, $refEnd, $path = null, $limit = null, $offset = null, $firstParent = false)
    {
        $this->clearAll();

        $this->addCommandName(self::GIT_LOG);
        $this->addCommandArgument('-s');
        $this->addCommandArgument('--pretty=raw');
        $this->addCommandArgument('--no-color');

        if (null !== $limit) {
            $limit = (int)$limit;
            $this->addCommandArgument('--max-count=' . $limit);
        }

        if (null !== $offset) {
            $offset = (int)$offset;
            $this->addCommandArgument('--skip=' . $offset);
        }

        if (true === $firstParent) {
            $this->addCommandArgument('--first-parent');
        }

        if ($refStart instanceof TreeishInterface) {
            $refStart = $refStart->getSha();
        }

        if ($refEnd instanceof TreeishInterface) {
            $refEnd = $refEnd->getSha();
        }

        if (null !== $path && !empty($path)) {
            $this->addPath($path);
        }

        $this->addCommandSubject($refStart . '..' . $refEnd);

        return $this->getCommand();
    }
}
