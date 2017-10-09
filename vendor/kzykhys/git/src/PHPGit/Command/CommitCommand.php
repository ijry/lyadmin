<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Exception\GitException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Record changes to the repository - `git commit`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class CommitCommand extends Command
{

    /**
     * Record changes to the repository
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
     * $git->setRepository('/path/to/repo');
     * $git->add('README.md');
     * $git->commit('Fixes README.md');
     * ```
     *
     * ##### Options
     *
     * - **all**           (_boolean_) Stage files that have been modified and deleted
     * - **reuse-message** (_string_)  Take an existing commit object, and reuse the log message and the authorship information (including the timestamp) when creating the commit
     * - **squash**        (_string_)  Construct a commit message for use with rebase --autosquash
     * - **author**        (_string_)  Override the commit author
     * - **date**          (_string_)  Override the author date used in the commit
     * - **cleanup**       (_string_)  Can be one of verbatim, whitespace, strip, and default
     * - **amend**         (_boolean_) Used to amend the tip of the current branch
     *
     * @param string $message Use the given <$msg> as the commit message
     * @param array  $options [optional] An array of options {@see CloneCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function __invoke($message, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('commit')
            ->add('-m')->add($message);

        $this->addFlags($builder, $options, array('all', 'amend'));
        $this->addValues($builder, $options, array('reuse-message', 'squash', 'author', 'date', 'cleanup'));

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **all**           (_boolean_) Stage files that have been modified and deleted
     * - **reuse-message** (_string_)  Take an existing commit object, and reuse the log message and the authorship information (including the timestamp) when creating the commit
     * - **squash**        (_string_)  Construct a commit message for use with rebase --autosquash
     * - **author**        (_string_)  Override the commit author
     * - **date**          (_string_)  Override the author date used in the commit
     * - **cleanup**       (_string_)  Can be one of verbatim, whitespace, strip, and default
     * - **amend**         (_boolean_) Used to amend the tip of the current branch
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'all'           => false,
            'reuse-message' => null,
            'squash'        => null,
            'author'        => null,
            'date'          => null,
            'cleanup'       => null,
            'amend'         => false
        ));

        $resolver->setAllowedValues(array(
            'cleanup' => array(null, 'default', 'verbatim', 'whitespace', 'strip')
        ));
    }

} 