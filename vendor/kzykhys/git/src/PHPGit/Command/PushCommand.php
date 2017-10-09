<?php

namespace PHPGit\Command;

use PHPGit\Command;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Update remote refs along with associated objects - `git push`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class PushCommand extends Command
{

    /**
     * Update remote refs along with associated objects
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->push('origin', 'master');
     * ```
     *
     * @param string $repository The "remote" repository that is destination of a push operation
     * @param string $refspec    Specify what destination ref to update with what source object
     * @param array  $options    [optional] An array of options {@see PushCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function __invoke($repository = null, $refspec = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('push');

        $this->addFlags($builder, $options);

        if ($repository) {
            $builder->add($repository);

            if ($refspec) {
                $builder->add($refspec);
            }
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'all'    => false,
            'mirror' => false,
            'tags'   => false,
            'force'  => false
        ));
    }

} 