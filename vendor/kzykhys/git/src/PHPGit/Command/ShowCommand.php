<?php

namespace PHPGit\Command;

use PHPGit\Command;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Show various types of objects - `git show`
 *
 * @author Kazuyuki Hayashi
 */
class ShowCommand extends Command
{

    /**
     * Shows one or more objects (blobs, trees, tags and commits)
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * echo $git->show('3ddee587e209661c8265d5bfd0df999836f6dfa2');
     * ```
     *
     * ##### Options
     *
     * - **format**        (_string_)  Pretty-print the contents of the commit logs in a given format, where <format> can be one of oneline, short, medium, full, fuller, email, raw and format:<string>
     * - **abbrev-commit** (_boolean_) Instead of showing the full 40-byte hexadecimal commit object name, show only a partial prefix
     *
     * @param string $object  The names of objects to show
     * @param array  $options [optional] An array of options {@see ShowCommand::setDefaultOptions}
     *
     * @return string
     */
    public function __invoke($object, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('show');

        $this->addFlags($builder, $options, array('abbrev-commit'));

        if ($options['format']) {
            $builder->add('--format=' . $options['format']);
        }

        $builder->add($object);

        return $this->git->run($builder->getProcess());
    }

    /**
     * {@inheritdoc}
     *
     * - **format**        (_string_)  Pretty-print the contents of the commit logs in a given format, where <format> can be one of oneline, short, medium, full, fuller, email, raw and format:<string>
     * - **abbrev-commit** (_boolean_) Instead of showing the full 40-byte hexadecimal commit object name, show only a partial prefix
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'format'        => null,
            'abbrev-commit' => false
        ));

        $resolver->setAllowedTypes(array(
            'format' => array('null', 'string'),
        ));
    }

}