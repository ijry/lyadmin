<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\ClassGuesserInterface;
use Joli\Jane\Guesser\Guess\MultipleType;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;

use Joli\Jane\Model\JsonSchema;

class AnyOfGuesser implements GuesserInterface, ClassGuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function guessClass($object, $name)
    {
        $classes = [];

        foreach ($object->getAnyOf() as $anyOfObject) {
            $classes = array_merge($classes, $this->chainGuesser->guessClass($anyOfObject, $name.'AnyOf'));
        }

        return $classes;
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        if (count($object->getAnyOf()) == 1) {
            return $this->chainGuesser->guessType($object->getAnyOf()[0], $name, $classes);
        }

        $type = new MultipleType($object);

        foreach ($object->getAnyOf() as $anyOfObject) {
            $type->addType($this->chainGuesser->guessType($anyOfObject, $name, $classes));
        }

        return $type;
    }

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof JsonSchema) && is_array($object->getAnyOf()) && count($object->getAnyOf()) > 0);
    }
}
 