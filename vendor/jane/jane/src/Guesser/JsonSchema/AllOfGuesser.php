<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\Type;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\Model\JsonSchema;
use Joli\Jane\Reference\Resolver;
use Joli\Jane\Runtime\Reference;

class AllOfGuesser implements GuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * @var \Joli\Jane\Reference\Resolver
     */
    private $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver         = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        $type = null;

        foreach ($object->getAllOf() as $allOf) {
            $allOfSchema = $allOf;

            if ($allOfSchema instanceof Reference) {
                $allOfSchema = $this->resolver->resolve($allOf);
            }

            if (null !== $allOfSchema->getType()) {
                if (null !== $type) {
                    throw new \RuntimeException('an allOf instruction with 2 or more types is strictly impossible, check your schema');
                }

                $type = $this->chainGuesser->guessType($allOf, $name, $classes);
            }
        }

        if ($type === null) {
            return new Type($object, 'mixed');
        }

        return $type;
    }

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof JsonSchema) && is_array($object->getAllOf()) && count($object->getAllOf()) > 0);
    }
}
