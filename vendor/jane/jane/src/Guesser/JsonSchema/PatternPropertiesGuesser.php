<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\MapType;
use Joli\Jane\Guesser\Guess\MultipleType;
use Joli\Jane\Guesser\Guess\PatternMapType;
use Joli\Jane\Guesser\Guess\PatternMultipleType;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;

use Joli\Jane\Model\JsonSchema;

class PatternPropertiesGuesser implements GuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface
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

        // @TODO Handle case when there is properties (need to rework the guessClass for extending \ArrayObject and do the assignation)
        if ($object->getProperties() !== null) {
            return false;
        }

        if (!($object->getPatternProperties() instanceof \ArrayObject) || count($object->getPatternProperties()) == 0) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        $type = new PatternMultipleType($object);

        foreach ($object->getPatternProperties() as $pattern => $patternProperty) {
            $type->addType($pattern, $this->chainGuesser->guessType($patternProperty, $name, $classes), $pattern);
        }

        return $type;
    }
}
