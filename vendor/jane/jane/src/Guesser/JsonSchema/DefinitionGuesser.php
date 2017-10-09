<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\ClassGuesserInterface;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Model\JsonSchema;

class DefinitionGuesser implements ChainGuesserAwareInterface, GuesserInterface, ClassGuesserInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function guessClass($object, $name)
    {
        $classes = [];

        foreach ($object->getDefinitions() as $key => $definition) {
            $classes = array_merge($classes, $this->chainGuesser->guessClass($definition, $key));
        }

        return $classes;
    }

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return ($object instanceof JsonSchema) && $object->getDefinitions() !== null && count($object->getDefinitions()) > 0;
    }
}
 