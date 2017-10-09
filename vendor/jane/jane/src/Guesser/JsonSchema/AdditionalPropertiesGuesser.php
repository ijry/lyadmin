<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\MapType;
use Joli\Jane\Guesser\Guess\Type;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\Model\JsonSchema;

class AdditionalPropertiesGuesser implements GuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        if (!($object instanceof JsonSchema)) {
            return false;
        }

        if ($object->getType() !== 'object') {
            return false;
        }

        if ($object->getAdditionalProperties() !== true && !is_object($object->getAdditionalProperties())) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        if ($object->getAdditionalProperties() === true) {
            return new MapType($object, new Type($object, 'mixed'));
        }

        return new MapType($object, $this->chainGuesser->guessType($object->getAdditionalProperties(), $name, $classes));
    }
}
 