<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Generator\Naming;
use Joli\Jane\Guesser\ChainGuesserAwareInterface;
use Joli\Jane\Guesser\ChainGuesserAwareTrait;
use Joli\Jane\Guesser\Guess\ClassGuess;
use Joli\Jane\Guesser\Guess\ObjectType;
use Joli\Jane\Guesser\Guess\Property;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\ClassGuesserInterface;
use Joli\Jane\Guesser\PropertiesGuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\Model\JsonSchema;
use Joli\Jane\Reference\Resolver;
use Joli\Jane\Runtime\Reference;

class ObjectGuesser implements GuesserInterface, PropertiesGuesserInterface, TypeGuesserInterface, ChainGuesserAwareInterface, ClassGuesserInterface
{
    use ChainGuesserAwareTrait;

    /**
     * @var \Joli\Jane\Generator\Naming
     */
    protected $naming;

    /**
     * @var \Joli\Jane\Reference\Resolver
     */
    protected $resolver;

    public function __construct(Naming $naming, Resolver $resolver)
    {
        $this->naming = $naming;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function supportObject($object)
    {
        return ($object instanceof JsonSchema) && $object->getType() === 'object' && $object->getProperties() !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function guessClass($object, $name)
    {
        $classes = [spl_object_hash($object) => new ClassGuess($object, $this->naming->getClassName($name))];

        foreach ($object->getProperties() as $key => $property) {
            $classes = array_merge($classes, $this->chainGuesser->guessClass($property, $key));
        }

        return $classes;
    }

    /**
     * {@inheritdoc}
     */
    public function guessProperties($object, $name, $classes)
    {
        $properties = [];

        foreach ($object->getProperties() as $key => $property) {
            $propertyObj = $property;

            if ($propertyObj instanceof Reference) {
                $propertyObj = $this->resolver->resolve($propertyObj);
            }

            $type = $propertyObj->getType();
            $nullable = $type == 'null' || (is_array($type) && in_array('null', $type));

            $properties[] = new Property($property, $key, $nullable);
        }

        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function guessType($object, $name, $classes)
    {
        $discriminants = [];
        $required = $object->getRequired() ?: [];

        foreach ($object->getProperties() as $key => $property) {
            if (!in_array($key, $required)) {
                continue;
            }

            if ($property instanceof Reference) {
                $property = $this->resolver->resolve($property);
            }

            if ($property->getEnum() !== null) {
                $isSimple = true;
                foreach ($property->getEnum() as $value) {
                    if (is_array($value) || is_object($value)) {
                        $isSimple = false;
                    }
                }
                if ($isSimple) {
                    $discriminants[$key] = $property->getEnum();
                }
            } else {
                $discriminants[$key] = null;
            }
        }

        return new ObjectType($object, $this->naming->getClassName($name), $discriminants);
    }
}
