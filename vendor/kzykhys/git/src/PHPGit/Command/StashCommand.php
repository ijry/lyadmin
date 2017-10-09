<?php

namespace PHPGit\Command;

use PHPGit\Command;

/**
 * Stash the changes in a dirty working directory away - `git stash`
 *
 * @author Kazuyuki Hayashi
 */
class StashCommand extends Command
{

    /**
     * Save your local modifications to a new stash, and run git reset --hard to revert them
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash();
     * ```
     *
     * @return bool
     */
    public function __invoke()
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash');

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Save your local modifications to a new stash, and run git reset --hard to revert them.
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash->save('My stash');
     * ```
     *
     * @param string $message [optional] The description along with the stashed state
     * @param array  $options [optional] An array of options {@see StashCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function save($message = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('save');

        $builder->add($message);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Returns the stashes that you currently have
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $stashes = $git->stash->lists();
     * ```
     *
     * ##### Output Example
     *
     * ``` php
     * [
     *     0 => ['branch' => 'master', 'message' => '0e2f473 Fixes README.md'],
     *     1 => ['branch' => 'master', 'message' => 'ce1ddde Initial commit'],
     * ]
     * ```
     *
     * @param array $options [optional] An array of options {@see StashCommand::setDefaultOptions}
     *
     * @return array
     */
    public function lists(array $options = array())
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('list');

        $output = $this->git->run($builder->getProcess());
        $lines  = $this->split($output);
        $list   = array();

        foreach ($lines as $line) {
            if (preg_match('/stash@{(\d+)}:.* [Oo]n (.*): (.*)/', $line, $matches)) {
                $list[$matches[1]] = array(
                    'branch'  => $matches[2],
                    'message' => $matches[3]
                );
            }
        }

        return $list;
    }

    /**
     * Show the changes recorded in the stash as a diff between the stashed state and its original parent
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * echo $git->stash->show('stash@{0}');
     * ```
     *
     * ##### Output Example
     *
     * ```
     *  REAMDE.md |    2 +-
     *  1 files changed, 1 insertions(+), 1 deletions(-)
     * ```
     *
     * @param string $stash The stash to show
     *
     * @return string
     */
    public function show($stash = null)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('show');

        if ($stash) {
            $builder->add($stash);
        }

        return $this->git->run($builder->getProcess());
    }

    /**
     * Remove a single stashed state from the stash list
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash->drop('stash@{0}');
     * ```
     *
     * @param string $stash The stash to drop
     *
     * @return mixed
     */
    public function drop($stash = null)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('drop');

        if ($stash) {
            $builder->add($stash);
        }

        return $this->git->run($builder->getProcess());
    }

    /**
     * Remove a single stashed state from the stash list and apply it on top of the current working tree state
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash->pop('stash@{0}');
     * ```
     *
     * @param string $stash   The stash to pop
     * @param array  $options [optional] An array of options {@see StashCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function pop($stash = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('pop');

        $this->addFlags($builder, $options, array('index'));

        if ($stash) {
            $builder->add($stash);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Like pop, but do not remove the state from the stash list
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash->apply('stash@{0}');
     * ```
     *
     * @param string $stash   The stash to apply
     * @param array  $options [optional] An array of options {@see StashCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function apply($stash = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('apply');

        $this->addFlags($builder, $options, array('index'));

        if ($stash) {
            $builder->add($stash);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Creates and checks out a new branch named <branchname> starting from the commit at which the <stash> was originally created, applies the changes recorded in <stash> to the new working tree and index
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash->branch('hotfix', 'stash@{0}');
     * ```
     *
     * @param string $name  The name of the branch
     * @param string $stash The stash
     *
     * @return bool
     */
    public function branch($name, $stash = null)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('branch')
            ->add($name);

        if ($stash) {
            $builder->add($stash);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Remove all the stashed states
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->stash->clear();
     * ```
     *
     * @return bool
     */
    public function clear()
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('clear');

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Create a stash (which is a regular commit object) and return its object name, without storing it anywhere in the ref namespace
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $commit = $git->stash->create();
     * ```
     *
     * ##### Output Example
     *
     * ```
     * 877316ea6f95c43b7ccc2c2a362eeedfa78b597d
     * ```
     *
     * @return string
     */
    public function create()
    {
        $builder = $this->git->getProcessBuilder()
            ->add('stash')
            ->add('create');

        return $this->git->run($builder->getProcess());
    }
    
} 