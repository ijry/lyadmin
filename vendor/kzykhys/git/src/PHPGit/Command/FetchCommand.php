<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Exception\GitException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Download objects and refs from another repository - `git fetch`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class FetchCommand extends Command
{

    /**
     * Fetches named heads or tags from one or more other repositories, along with the objects necessary to complete them
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'git://your/repo.git');
     * $git->fetch('origin');
     * ```
     *
     * ##### Options
     *
     * - **append** (_boolean_) Append ref names and object names of fetched refs to the existing contents of .git/FETCH_HEAD
     * - **keep**   (_boolean_) Keep downloaded pack
     * - **prune**  (_boolean_) After fetching, remove any remote-tracking branches which no longer exist on the remote
     *
     * @param string $repository The "remote" repository that is the source of a fetch or pull operation
     * @param string $refspec    The format of a <refspec> parameter is an optional plus +, followed by the source ref <src>,
     *                            followed by a colon :, followed by the destination ref <dst>
     * @param array  $options    [optional] An array of options {@see FetchCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function __invoke($repository, $refspec = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('fetch');

        $this->addFlags($builder, $options);
        $builder->add($repository);

        if ($refspec) {
            $builder->add($refspec);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Fetch all remotes
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'git://your/repo.git');
     * $git->remote->add('release', 'git://your/another_repo.git');
     * $git->fetch->all();
     * ```
     *
     * ##### Options
     *
     * - **append** (_boolean_) Append ref names and object names of fetched refs to the existing contents of .git/FETCH_HEAD
     * - **keep**   (_boolean_) Keep downloaded pack
     * - **prune**  (_boolean_) After fetching, remove any remote-tracking branches which no longer exist on the remote
     *
     * @param array $options [optional] An array of options {@see FetchCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function all(array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('fetch')
            ->add('--all');

        $this->addFlags($builder, $options);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **append** (_boolean_) Append ref names and object names of fetched refs to the existing contents of .git/FETCH_HEAD
     * - **keep**   (_boolean_) Keep downloaded pack
     * - **prune**  (_boolean_) After fetching, remove any remote-tracking branches which no longer exist on the remote
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'append' => false,
            //'force'  => false,
            'keep'   => false,
            'prune'  => false,
        ));
    }

}