<?php

namespace PHPGit\Command\Remote;

use PHPGit\Command;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Changes URL remote points to
 *
 * @author Kazuyuki Hayashi
 */
class SetUrlCommand extends Command
{

    /**
     * Alias of set()
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->url('origin', 'https://github.com/text/Text.git');
     * ```
     *
     * ##### Options
     *
     * - **push** (_boolean_) Push URLs are manipulated instead of fetch URLs
     *
     * @param string $name    The name of remote
     * @param string $newUrl  The new URL
     * @param string $oldUrl  [optional] The old URL
     * @param array  $options [optional] An array of options {@see SetUrlCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function __invoke($name, $newUrl, $oldUrl = null, array $options = array())
    {
        return $this->set($name, $newUrl, $oldUrl, $options);
    }

    /**
     * Sets the URL remote to $newUrl
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->url->set('origin', 'https://github.com/text/Text.git');
     * ```
     *
     * ##### Options
     *
     * - **push** (_boolean_) Push URLs are manipulated instead of fetch URLs
     *
     * @param string $name    The name of remote
     * @param string $newUrl  The new URL
     * @param string $oldUrl  [optional] The old URL
     * @param array  $options [optional] An array of options {@see SetUrlCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function set($name, $newUrl, $oldUrl = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-url');

        $this->addFlags($builder, $options);

        $builder
            ->add($name)
            ->add($newUrl);

        if ($oldUrl) {
            $builder->add($oldUrl);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Adds new URL to remote
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->url->add('origin', 'https://github.com/text/Text.git');
     * ```
     *
     * ##### Options
     *
     * - **push** (_boolean_) Push URLs are manipulated instead of fetch URLs
     *
     * @param string $name    The name of remote
     * @param string $newUrl  The new URL
     * @param array  $options [optional] An array of options {@see SetUrlCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function add($name, $newUrl, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-url')
            ->add('--add');

        $this->addFlags($builder, $options);

        $builder
            ->add($name)
            ->add($newUrl);

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Deletes all URLs matching regex $url
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->remote->add('origin', 'https://github.com/kzykhys/Text.git');
     * $git->remote->url->delete('origin', 'https://github.com');
     * ```
     *
     * ##### Options
     *
     * - **push** (_boolean_) Push URLs are manipulated instead of fetch URLs
     *
     * @param string $name    The remote name
     * @param string $url     The URL to delete
     * @param array  $options [optional] An array of options {@see SetUrlCommand::setDefaultOptions}
     *
     * @return bool
     */
    public function delete($name, $url, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('remote')
            ->add('set-url')
            ->add('--delete');

        $this->addFlags($builder, $options);

        $builder
            ->add($name)
            ->add($url);

        $this->git->run($builder->getProcess());

        return true;
    }
    
    /**
     * {@inheritdoc}
     *
     * - **push** (_boolean_) Push URLs are manipulated instead of fetch URLs
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'push' => false
        ));
    }

}