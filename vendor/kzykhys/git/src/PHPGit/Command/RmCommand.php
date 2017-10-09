<?php

namespace PHPGit\Command;

use PHPGit\Command;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Remove files from the working tree and from the index - `git rm`
 *
 * @author Kazuyuki Hayashi
 */
class RmCommand extends Command
{

    /**
     * Remove files from the working tree and from the index
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->rm('CHANGELOG-1.0-1.1.txt', ['force' => true]);
     * ```
     *
     * ##### Options
     *
     * - **force**     (_boolean_) Override the up-to-date check
     * - **cached**    (_boolean_) Unstage and remove paths only from the index
     * - **recursive** (_boolean_) Allow recursive removal when a leading directory name is given
     *
     * @param string|array|\Traversable $file    Files to remove. Fileglobs (e.g.  *.c) can be given to remove all matching files.
     * @param array                     $options [optional] An array of options {@see RmCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function __invoke($file, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('rm');

        $this->addFlags($builder, $options, array('force', 'cached'));

        if ($options['recursive']) {
            $builder->add('-r');
        }

        if (!is_array($file) && !($file instanceof \Traversable)) {
            $file = array($file);
        }

        foreach ($file as $value) {
            $builder->add($value);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Equivalent to $git->rm($file, ['cached' => true]);
     *
     * ##### Options
     *
     * - **force**     (_boolean_) Override the up-to-date check
     * - **recursive** (_boolean_) Allow recursive removal when a leading directory name is given
     *
     * @param string|array|\Traversable $file    Files to remove. Fileglobs (e.g.  *.c) can be given to remove all matching files.
     * @param array                     $options [optional] An array of options {@see RmCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function cached($file, array $options = array())
    {
        $options['cached'] = true;

        return $this->__invoke($file, $options);
    }

    /**
     * {@inheritdoc}
     *
     * - **force**     (_boolean_) Override the up-to-date check
     * - **cached**    (_boolean_) Unstage and remove paths only from the index
     * - **recursive** (_boolean_) Allow recursive removal when a leading directory name is given
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'force'     => false,
            'cached'    => false,
            'recursive' => false
        ));
    }

}