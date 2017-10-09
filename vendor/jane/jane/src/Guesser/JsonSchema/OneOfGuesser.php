<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\MultipleType;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\Model\JsonSchema;

class OneOfGuesser implements ChainGuesserAwareInterface, TypeGuesserInterface, GuesserInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof JsonSchema) && $object->getType() !== "object" && is_array($object->getOneOf()) && count($object->getOneOf()) > 0);
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        $type = new MultipleType($object);

        foreach ($object->getOneOf() as $oneOf) {
            $type->addType($this->chainGuesser->guessType($oneOf, $name, $classes));
        }

        return $type;
    }
}
 