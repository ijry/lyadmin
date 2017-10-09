<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\ClassGuesserInterface;
use Joli\Jane\Guesser\Guess\MultipleType;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\JsonSchemaMerger;
use Joli\Jane\Model\JsonSchema;
use Joli\Jane\Reference\Resolver;
use Joli\Jane\Runtime\Reference;

class ObjectOneOfGuesser implements GuesserInterface, TypeGuesserInterface, ClassGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    /**
     * @var \Joli\Jane\JsonSchemaMerger
     */
    private $jsonSchemaMerger;

    /**
     * @var \Joli\Jane\Reference\Resolver
     */
    private $resolver;

    public function __construct(JsonSchemaMerger $jsonSchemaMerger, Resolver $resolver)
    {
        $this->jsonSchemaMerger = $jsonSchemaMerger;
        $this->resolver         = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function guessClass($object, $name)
    {
        $classes   = [];

        foreach ($object->getOneOf() as $oneOf) {
            $oneOfName = $name.'Sub';
            $oneOfResolved = $oneOf;

            if ($oneOf instanceof Reference) {
                $oneOfName     = array_pop(explode('/', $oneOf->getFragment()));
                $oneOfResolved = $this->resolver->resolve($oneOf);
            }

            $merged  = $this->jsonSchemaMerger->merge($object, $oneOfResolved);
            $classes = array_merge($classes, $this->chainGuesser->guessClass($merged, $oneOfName));

            if ($oneOf instanceof Reference) {
                $oneOf->setResolved($merged);
            }
        }

        return $classes;
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

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof JsonSchema) && $object->getType() === "object" && is_array($object->getOneOf()) && count($object->getOneOf()) > 0);
    }
}
