<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Exception\GitException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Add file contents to the index - `git add`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class AddCommand extends Command
{

    /**
     * Add file contents to the index
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->add('file.txt');
     * $git->add('file.txt', ['force' => false, 'ignore-errors' => false);
     * ```
     *
     * ##### Options
     *
     * - **force**          (_boolean_) Allow adding otherwise ignored files
     * - **ignore-errors**  (_boolean_) Do not abort the operation
     *
     * @param string|array|\Traversable $file    Files to add content from
     * @param array                     $options [optional] An array of options {@see AddCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function __invoke($file, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('add');

        $this->addFlags($builder, $options);

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
     * {@inheritdoc}
     *
     * - **force**          (_boolean_) Allow adding otherwise ignored files
     * - **ignore-errors**  (_boolean_) Do not abort the operation
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'dry-run'        => false,
            'force'          => false,
            'ignore-errors'  => false,
            //'ignore-missing' => false,
        ));
    }

}