<?php

namespace PHPGit\Command\Remote;

use PHPGit\Command;

/**
 * Changes the list of branches tracked by the named remote
 *
 * @author Kazuyuki Hayashi
 */
class SetBranchesCommand extends Command
{

    /**
     * Alias of set()
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->branches('origin', array('master', 'develop'));
     * ```
     *
     * @param string $name     The remote name
     * @param array  $branches The names of the tracked branch
     *
     * @return bool
     */
    public function __invoke($name, array $branches)
    {
        return $this->set($name, $branches);
    }

    /**
     * Changes the list of branches tracked by the named remote
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->branches->set('origin', array('master', 'develop'));
     * ```
     *
     * @param string $name     The remote name
     * @param array  $branches The names of the tracked branch
     *
     * @return bool
     */
    public function set($name, array $branches)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-branches')
            ->add($name);

        foreach ($branches as $branch) {
            $builder->add($branch);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Adds to the list of branches tracked by the named remote
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->branches->add('origin', array('master', 'develop'));
     * ```
     *
     * @param string $name     The remote name
     * @param array  $branches The names of the tracked branch
     *
     * @return bool
     */
    public function add($name, array $branches)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-branches')
            ->add($name)
            ->add('--add');

        foreach ($branches as $branch) {
            $builder->add($branch);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

} 