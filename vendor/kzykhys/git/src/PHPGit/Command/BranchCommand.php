<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Exception\GitException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * List, create, or delete branches - `git branch`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class BranchCommand extends Command
{

    /**
     * Returns an array of both remote-tracking branches and local branches
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $branches = $git->branch();
     * ```
     *
     * ##### Output Example
     *
     * ```
     * [
     *     'master' => ['current' => true, 'name' => 'master', 'hash' => 'bf231bb', 'title' => 'Initial Commit'],
     *     'origin/master' => ['current' => false, 'name' => 'origin/master', 'alias' => 'remotes/origin/master']
     * ]
     * ```
     *
     * ##### Options
     *
     * - **all**     (_boolean_) List both remote-tracking branches and local branches
     * - **remotes** (_boolean_) List the remote-tracking branches
     *
     * @param array $options [optional] An array of options {@see BranchCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return array
     */
    public function __invoke(array $options = array())
    {
        $options  = $this->resolve($options);
        $branches = array();
        $builder  = $this->getProcessBuilder()
            ->add('-v')->add('--abbrev=7');

        if ($options['remotes']) {
            $builder->add('--remotes');
        }

        if ($options['all']) {
            $builder->add('--all');
        }

        $process = $builder->getProcess();
        $this->git->run($process);

        $lines = preg_split('/\r?\n/', rtrim($process->getOutput()), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($lines as $line) {
            $branch = array();
            preg_match('/(?<current>\*| ) (?<name>[^\s]+) +((?:->) (?<alias>[^\s]+)|(?<hash>[0-9a-z]{7}) (?<title>.*))/', $line, $matches);

            $branch['current'] = ($matches['current'] == '*');
            $branch['name']    = $matches['name'];

            if (isset($matches['hash'])) {
                $branch['hash']  = $matches['hash'];
                $branch['title'] = $matches['title'];
            } else {
                $branch['alias'] = $matches['alias'];
            }

            $branches[$matches['name']] = $branch;
        }

        return $branches;
    }

    /**
     * Creates a new branch head named **$branch** which points to the current HEAD, or **$startPoint** if given
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->branch->create('bugfix');              // from current HEAD
     * $git->branch->create('patch-1', 'a092bf7s'); // from commit
     * $git->branch->create('1.0.x-fix', 'v1.0.2'); // from tag
     * ```
     *
     * ##### Options
     *
     * - **force**   (_boolean_) Reset **$branch**  to **$startPoint** if **$branch** exists already
     *
     * @param string $branch     The name of the branch to create
     * @param string $startPoint [optional] The new branch head will point to this commit.
     *                            It may be given as a branch name, a commit-id, or a tag.
     *                            If this option is omitted, the current HEAD will be used instead.
     * @param array  $options    [optional] An array of options {@see BranchCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function create($branch, $startPoint = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->getProcessBuilder();

        if ($options['force']) {
            $builder->add('-f');
        }

        $builder->add($branch);

        if ($startPoint) {
            $builder->add($startPoint);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Move/rename a branch and the corresponding reflog
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->branch->move('bugfix', '2.0');
     * ```
     *
     * ##### Options
     *
     * - **force**   (_boolean_) Move/rename a branch even if the new branch name already exists
     *
     * @param string $branch    The name of an existing branch to rename
     * @param string $newBranch The new name for an existing branch
     * @param array  $options   [optional] An array of options {@see BranchCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function move($branch, $newBranch, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->getProcessBuilder();

        if ($options['force']) {
            $builder->add('-M');
        } else {
            $builder->add('-m');
        }

        $builder->add($branch)->add($newBranch);
        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Delete a branch
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->branch->delete('2.0');
     * ```
     *
     * The branch must be fully merged in its upstream branch, or in HEAD if no upstream was set with --track or --set-upstream.
     *
     * ##### Options
     *
     * - **force**   (_boolean_) Delete a branch irrespective of its merged status
     *
     * @param string $branch  The name of the branch to delete
     * @param array  $options [optional] An array of options {@see BranchCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function delete($branch, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->getProcessBuilder();

        if ($options['force']) {
            $builder->add('-D');
        } else {
            $builder->add('-d');
        }

        $builder->add($branch);
        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **force**   (_boolean_) Reset <branchname> to <startpoint> if <branchname> exists already
     * - **all**     (_boolean_) List both remote-tracking branches and local branches
     * - **remotes** (_boolean_) List or delete (if used with delete()) the remote-tracking branches
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'force'   => false,
            'all'     => false,
            'remotes' => false,
        ));
    }

    /**
     * @return \Symfony\Component\Process\ProcessBuilder
     */
    protected function getProcessBuilder()
    {
        return $this->git->getProcessBuilder()
            ->add('branch');
    }

} 