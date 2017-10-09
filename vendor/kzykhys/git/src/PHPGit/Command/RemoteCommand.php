<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Git;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Manage set of tracked repositories - `git remote`
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 *
 * @method head($name, $branch)                                     Sets the default branch for the named remote
 * @method branches($name, $branches)                               Changes the list of branches tracked by the named remote
 * @method url($name, $newUrl, $oldUrl = null, $options = array()) Sets the URL remote to $newUrl
 */
class RemoteCommand extends Command
{

    /** @var Remote\SetHeadCommand */
    public $head;

    /** @var Remote\SetBranchesCommand */
    public $branches;

    /** @var Remote\SetUrlCommand */
    public $url;

    /**
     * @param Git $git
     */
    public function __construct(Git $git)
    {
        parent::__construct($git);

        $this->head     = new Remote\SetHeadCommand($git);
        $this->branches = new Remote\SetBranchesCommand($git);
        $this->url      = new Remote\SetUrlCommand($git);
    }

    /**
     * Calls sub-commands
     *
     * @param string $name      The name of a property
     * @param array  $arguments An array of arguments
     *
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (isset($this->{$name}) && is_callable($this->{$name})) {
            return call_user_func_array($this->{$name}, $arguments);
        }

        throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', __CLASS__, $name));
    }

    /**
     * Returns an array of existing remotes
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->clone('https://github.com/kzykhys/Text.git', '/path/to/repo');
     * $git->setRepository('/path/to/repo');
     * $remotes = $git->remote();
     * ```
     *
     * ##### Output Example
     *
     * ``` php
     * [
     *     'origin' => [
     *         'fetch' => 'https://github.com/kzykhys/Text.git',
     *         'push'  => 'https://github.com/kzykhys/Text.git'
     *     ]
     * ]
     * ```
     *
     * @return array
     */
    public function __invoke()
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('-v');

        $remotes = array();
        $output  = $this->git->run($builder->getProcess());
        $lines   = $this->split($output);

        foreach ($lines as $line) {
            if (preg_match('/^(.*)\t(.*)\s\((.*)\)$/', $line, $matches)) {
                if (!isset($remotes[$matches[1]])) {
                    $remotes[$matches[1]] = array();
                }

                $remotes[$matches[1]][$matches[3]] = $matches[2];
            }
        }

        return $remotes;
    }

    /**
     * Adds a remote named **$name** for the repository at **$url**
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->fetch('origin');
     * ```
     *
     * ##### Options
     *
     * - **tags**    (_boolean_) With this option, `git fetch <name>` imports every tag from the remote repository
     * - **no-tags** (_boolean_) With this option, `git fetch <name>` does not import tags from the remote repository
     *
     * @param string $name    The name of the remote
     * @param string $url     The url of the remote
     * @param array  $options [optional] An array of options {@see RemoteCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function add($name, $url, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('add');

        $this->addFlags($builder, $options, array('tags', 'no-tags'));

        $builder->add($name)->add($url);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Rename the remote named **$name** to **$newName**
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->rename('origin', 'upstream');
     * ```
     *
     * @param string $name    The remote name to rename
     * @param string $newName The new remote name
     *
     * @return bool
     */
    public function rename($name, $newName)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('rename')
            ->add($name)
            ->add($newName);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Remove the remote named **$name**
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->rm('origin');
     * ```
     *
     * @param string $name The remote name to remove
     *
     * @return bool
     */
    public function rm($name)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('rm')
            ->add($name);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Gives some information about the remote **$name**
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->clone('https://github.com/kzykhys/Text.git', '/path/to/repo');
     * $git->setRepository('/path/to/repo');
     * echo $git->remote->show('origin');
     * ```
     *
     * ##### Output Example
     *
     * ```
     * \* remote origin
     *   Fetch URL: https://github.com/kzykhys/Text.git
     *   Push  URL: https://github.com/kzykhys/Text.git
     *   HEAD branch: master
     *   Remote branch:
     *     master tracked
     *   Local branch configured for 'git pull':
     *     master merges with remote master
     *   Local ref configured for 'git push':
     *     master pushes to master (up to date)
     * ```
     *
     * @param string $name The remote name to show
     *
     * @return string
     */
    public function show($name)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('show')
            ->add($name);

        return $this->git->run($builder->getProcess());
    }

    /**
     * Deletes all stale remote-tracking branches under **$name**
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->prune('origin');
     * ```
     *
     * @param string $name The remote name
     *
     * @return bool
     */
    public function prune($name = null)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('prune');

        if ($name) {
            $builder->add($name);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **tags**    (_boolean_) With this option, `git fetch <name>` imports every tag from the remote repository
     * - **no-tags** (_boolean_) With this option, `git fetch <name>` does not import tags from the remote repository
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'tags'    => false,
            'no-tags' => false
        ));
    }

}