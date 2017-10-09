<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\ClassGuesserInterface;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Model\JsonSchema;

class AdditionalItemsGuesser implements ChainGuesserAwareInterface, GuesserInterface, ClassGuesserInterface
{
    use ChainGuesserAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function guessClass($object, $name)
    {
        return $this->chainGuesser->guessClass($object->getAdditionalItems(), $name . 'AdditionalItems');
    }

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return ($object instanceof JsonSchema) && ($object->getAdditionalItems() instanceof JsonSchema);
    }
}
 