<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\ClassGuesserInterface;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Model\JsonSchema;

class ItemsGuesser implements GuesserInterface, ClassGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function guessClass($object, $name)
    {
        if ($object->getItems() instanceof JsonSchema) {
            return $this->chainGuesser->guessClass($object->getAdditionalItems(), $name . 'Item');
        }

        $classes = [];
        $count   = 1;

        foreach ($object->getItems() as $item) {
            $classes = array_merge($classes, $this->chainGuesser->guessClass($item, $name . 'Item' . $count));
            $count++;
        }

        return $classes;
    }

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (
            ($object instanceof JsonSchema)
            && (
                $object->getItems() instanceof JsonSchema
                ||
                (
                    is_array($object->getItems())
                    &&
                    count($object->getItems()) > 0
                )
            )
        );
    }
}
 