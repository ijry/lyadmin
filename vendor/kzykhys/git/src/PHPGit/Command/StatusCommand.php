<?php

namespace PHPGit\Command;

use PHPGit\Command;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Show the working tree status - `git status`
 *
 *   = unmodified
 * M = modified
 * A = added
 * D = deleted
 * R = renamed
 * C = copied
 * U = updated but unmerged
 *
 * X          Y     Meaning
 * -------------------------------------------------
 * [MD]   not updated
 * M        [ MD]   updated in index
 * A        [ MD]   added to index
 * D         [ M]   deleted from index
 * R        [ MD]   renamed in index
 * C        [ MD]   copied in index
 * [MARC]           index and work tree matches
 * [ MARC]     M    work tree changed since index
 * [ MARC]     D    deleted in work tree
 * -------------------------------------------------
 * D           D    unmerged, both deleted
 * A           U    unmerged, added by us
 * U           D    unmerged, deleted by them
 * U           A    unmerged, added by them
 * D           U    unmerged, deleted by us
 * A           A    unmerged, both added
 * U           U    unmerged, both modified
 * -------------------------------------------------
 * ?           ?    untracked
 * !           !    ignored
 * -------------------------------------------------
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class StatusCommand extends Command
{

    const UNMODIFIED           = ' ';
    const MODIFIED             = 'M';
    const ADDED                = 'A';
    const DELETED              = 'D';
    const RENAMED              = 'R';
    const COPIED               = 'C';
    const UPDATED_BUT_UNMERGED = 'U';
    const UNTRACKED            = '?';
    const IGNORED              = '!';

    /**
     * Returns the working tree status
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $status = $git->status();
     * ```
     *
     * ##### Constants
     *
     * - StatusCommand::UNMODIFIED            [=' '] unmodified
     * - StatusCommand::MODIFIED              [='M'] modified
     * - StatusCommand::ADDED                 [='A'] added
     * - StatusCommand::DELETED               [='D'] deleted
     * - StatusCommand::RENAMED               [='R'] renamed
     * - StatusCommand::COPIED                [='C'] copied
     * - StatusCommand::UPDATED_BUT_UNMERGED  [='U'] updated but unmerged
     * - StatusCommand::UNTRACKED             [='?'] untracked
     * - StatusCommand::IGNORED               [='!'] ignored
     *
     * ##### Output Example
     *
     * ``` php
     * [
     *     'branch' => 'master',
     *     'changes' => [
     *         ['file' => 'item1.txt', 'index' => 'A', 'work_tree' => 'M'],
     *         ['file' => 'item2.txt', 'index' => 'A', 'work_tree' => ' '],
     *         ['file' => 'item3.txt', 'index' => '?', 'work_tree' => '?'],
     *     ]
     * ]
     * ```
     *
     * ##### Options
     *
     * - **ignored** (_boolean_) Show ignored files as well
     *
     * @param array $options [optional] An array of options {@see StatusCommand::setDefaultOptions}
     *
     * @return mixed
     */
    public function __invoke(array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('status')
            ->add('--porcelain')->add('-s')->add('-b')->add('--null');

        $this->addFlags($builder, $options);

        $process = $builder->getProcess();
        $result  = array('branch' => null, 'changes' => array());
        $output  = $this->git->run($process);

        list($branch, $changes) = preg_split('/(\0|\n)/', $output, 2);
        $lines = $this->split($changes, true);

        if (substr($branch, -11) == '(no branch)') {
            $result['branch'] = null;
        } elseif (preg_match('/([^ ]*)\.\.\..*?\[.*?\]$/', $branch, $matches)) {
            $result['branch'] = $matches[1];
        } elseif (preg_match('/ ([^ ]*)$/', $branch, $matches)) {
            $result['branch'] = $matches[1];
        }

        foreach ($lines as $line) {
            $result['changes'][] = array(
                'file'      => substr($line, 3),
                'index'     => substr($line, 0, 1),
                'work_tree' => substr($line, 1, 1)
            );
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * - **ignored** (_boolean_) Show ignored files as well
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'ignored' => false
        ));
    }

}