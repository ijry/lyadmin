<?php

namespace PHPGit\Command;

use PHPGit\Command;
use PHPGit\Exception\GitException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Create, list, delete or verify a tag object signed with GPG - `git tag`
 *
 * @author Kazuyuki Hayashi
 */
class TagCommand extends Command
{

    /**
     * Returns an array of tags
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->clone('https://github.com/kzykhys/PHPGit.git', '/path/to/repo');
     * $git->setRepository('/path/to/repo');
     * $tags = $git->tag();
     * ```
     *
     * ##### Output Example
     *
     * ```
     * ['v1.0.0', 'v1.0.1', 'v1.0.2']
     * ```
     *
     * @throws GitException
     * @return array
     */
    public function __invoke()
    {
        $builder = $this->git->getProcessBuilder()
            ->add('tag');

        $output = $this->git->run($builder->getProcess());

        return $this->split($output);
    }

    /**
     * Creates a tag object
     *
     * ``` php
     * $git = new PHPGit\Git();
     * $git->setRepository('/path/to/repo');
     * $git->tag->create('v1.0.0');
     * ```
     *
     * ##### Options
     *
     * - **annotate** (_boolean_) Make an unsigned, annotated tag object
     * - **sign**     (_boolean_) Make a GPG-signed tag, using the default e-mail address’s key
     * - **force**    (_boolean_) Replace an existing tag with the given name (instead of failing)
     *
     * @param string $tag     The name of the tag to create
     * @param string $commit  The SHA1 object name of the commit object
     * @param array  $options [optional] An array of options {@see TagCommand::setDefaultOptions}
     *
     * @throws GitException
     * @return bool
     */
    public function create($tag, $commit = null, array $options = array())
    {
        $options = $this->resolve($options);
        $builder = $this->git->getProcessBuilder()
            ->add('tag')
            ->add($tag);

        $this->addFlags($builder, $options, array('annotate', 'sign', 'force'));

        if ($commit) {
            $builder->add($commit);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Delete existing tags with the given names
     *
     * @param string|array|\Traversable $tag The name of the tag to create
     *
     * @throws GitException
     * @return bool
     */
    public function delete($tag)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('tag')
            ->add('-d');

        if (!is_array($tag) && !($tag instanceof \Traversable)) {
            $tag = array($tag);
        }

        foreach ($tag as $value) {
            $builder->add($value);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * Verify the gpg signature of the given tag names
     *
     * @param string|array|\Traversable $tag The name of the tag to create
     *
     * @throws GitException
     * @return bool
     */
    public function verify($tag)
    {
        $builder = $this->git->getProcessBuilder()
            ->add('tag')
            ->add('-v');

        if (!is_array($tag) && !($tag instanceof \Traversable)) {
            $tag = array($tag);
        }

        foreach ($tag as $value) {
            $builder->add($value);
        }

        $this->git->run($builder->getProcess());

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * - **annotate** (_boolean_) Make an unsigned, annotated tag object
     * - **sign**     (_boolean_) Make a GPG-signed tag, using the default e-mail address’s key
     * - **force**    (_boolean_) Replace an existing tag with the given name (instead of failing)
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'annotate' => false,
            'sign' => false,
            'force' => false,
        ));
    }

} 