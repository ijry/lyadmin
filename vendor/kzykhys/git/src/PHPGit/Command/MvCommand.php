<?php

namespace PHPGit\Command;

use PHPGit\Command;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Move or rename a file, a directory, or a symlink - `git mv`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class MvCommand extends Command
{

    /**
     * Move or rename a file, a directory, or a symlink
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->mv('UPGRADE-1.0.md', 'UPGRADE-1.1.md');
     * ```
     *
     * ##### Options
     *
     * - **force** (_boolean_) Force renaming or moving of a file even if the target exists
     *
     * @param string|array|\Iterator $source      The files to move
     * @param string                 $destination The destination
     * @param array                  $options     [optional] An array of options {@see MvCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function __invoke($source, $destination, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('mv');

        $this->addFlags($builder, $options, array('force'));

        if (!is_array($source) && !($source instanceof \Traversable)) {
            $source = array($source);
        }

        foreach ($source as $value) {
            $builder->add($value);
        }

        $builder->add($destination);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **force** (_boolean_) Force renaming or moving of a file even if the target exists
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'force' => false
        ));
    }

} 