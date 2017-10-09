<?php

namespace PHPGit\Command\Remote;

use PHPGit\Command;

/**
 * Sets or deletes the default branch (i.e. the target of the symbolic-ref refs/remotes/<name>/HEAD) for the named remote
 *
 * @author Kazuyuki Hayashi
 */
class SetHeadCommand extends Command
{

    /**
     * Alias of set()
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->head('origin');
     * ```
     *
     * @param string $name   The remote name
     * @param string $branch [optional] The symbolic-ref to set
     *
     * @return bool
     */
    public function __invoke($name, $branch = null)
    {
        return $this->set($name, $branch);
    }

    /**
     * Sets the default branch for the named remote
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->head->set('origin');
     * ```
     *
     * @param string $name   The remote name
     * @param string $branch [optional] The symbolic-ref to set
     *
     * @return bool
     */
    public function set($name, $branch)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-head')
            ->add($name);

        if ($branch) {
            $builder->add($branch);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Deletes the default branch for the named remote
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->head->delete('origin');
     * ```
     *
     * @param string $name The remote name
     *
     * @return bool
     */
    public function delete($name)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-head')
            ->add($name)
            ->add('-d');

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Determine the default branch by querying remote
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->head->remote('origin');
     * ```
     *
     * @param string $name The remote name
     *
     * @return bool
     */
    public function remote($name)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-head')
            ->add($name)
            ->add('-a');

        $this->git->run($builder->getProcess());

        return true;
    }

} 