<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\MultipleType;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\Model\JsonSchema;

class MultipleGuesser implements GuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function supportObject($object)
    {
        return ($object instanceof JsonSchema) && is_array($object->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function guessType($object, $name, $classes)
    {
        $typeGuess = new MultipleType($object);
        $fakeSchema = clone $object;

        foreach ($object->getType() as $type) {
            $fakeSchema->setType($type);

            $typeGuess->addType($this->chainGuesser->guessType($fakeSchema, $name, $classes));
        }

        return $typeGuess;
    }
}
