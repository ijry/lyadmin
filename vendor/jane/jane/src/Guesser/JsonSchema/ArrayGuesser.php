<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\ArrayType;
use Joli\Jane\Guesser\Guess\MultipleType;
use Joli\Jane\Guesser\Guess\Type;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;

use Joli\Jane\Model\JsonSchema;

class ArrayGuesser implements GuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof JsonSchema) && $object->getType() === 'array');
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        $items = $object->getItems();

        if ($items === null) {
            return new ArrayType($object, new Type($object, 'mixed'));
        }

        if (!is_array($items)) {
            return new ArrayType($object, $this->chainGuesser->guessType($items, $name, $classes));
        }

        $type = new MultipleType($object);

        foreach ($items as $item) {
            $type->addType(new ArrayType($object, $this->chainGuesser->guessType($item, $name, $classes)));
        }

        return $type;
    }
}
 