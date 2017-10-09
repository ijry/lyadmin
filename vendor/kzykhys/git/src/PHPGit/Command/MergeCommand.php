<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Exception\GitException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Join two or more development histories together - `git merge`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class MergeCommand extends Command
{

    /**
     * Incorporates changes from the named commits into the current branch
     *
     * ```php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->merge('1.0');
     * $git->merge('1.1', 'Merge message', ['strategy' => 'ours']);
     * ```
     *
     * ##### Options
     *
     * - **no-ff**               (_boolean_) Do not generate a merge commit if the merge resolved as a fast-forward, only update the branch pointer
     * - **rerere-autoupdate**   (_boolean_) Allow the rerere mechanism to update the index with the result of auto-conflict resolution if possible
     * - **squash**              (_boolean_) Allows you to create a single commit on top of the current branch whose effect is the same as merging another branch
     * - **strategy**            (_string_)  Use the given merge strategy
     * - **strategy-option**     (_string_)  Pass merge strategy specific option through to the merge strategy
     *
     * @param string|array|\Traversable $commit  Commits to merge into our branch
     * @param string                    $message [optional] Commit message to be used for the merge commit
     * @param array                     $options [optional] An array of options {@see MergeCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function __invoke($commit, $message = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('merge');

        $this->addFlags($builder, $options, array('no-ff', 'rerere-autoupdate', 'squash'));

        if (!is_array($commit) && !($commit instanceof \Traversable)) {
            $commit = array($commit);
        }
        foreach ($commit as $value) {
            $builder->add($value);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Abort the merge process and try to reconstruct the pre-merge state
     *
     * ```php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * try {
     *     $git->merge('dev');
     * } catch (PHPGit\Exception\GitException $e) {
     *     $git->merge->abort();
     * }
     * ```
     *
     * @throws GitException
     * @return bool
     */
    public function abort()
    {
        $builder = $this->git->getProcessBuilder()
            ->add('merge')
            ->add('--abort');

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **no-ff**               (_boolean_) Do not generate a merge commit if the merge resolved as a fast-forward, only update the branch pointer
     * - **rerere-autoupdate**   (_boolean_) Allow the rerere mechanism to update the index with the result of auto-conflict resolution if possible
     * - **squash**              (_boolean_) Allows you to create a single commit on top of the current branch whose effect is the same as merging another branch
     * - **strategy**            (_string_)  Use the given merge strategy
     * - **strategy-option**     (_string_)  Pass merge strategy specific option through to the merge strategy
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'no-ff'             => false,
            'rerere-autoupdate' => false,
            'squash'            => false,

            'strategy'          => null,
            'strategy-option'   => null
        ));
    }

} 