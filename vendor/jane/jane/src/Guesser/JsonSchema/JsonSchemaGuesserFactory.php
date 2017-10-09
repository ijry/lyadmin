<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Generator\Model\ClassGenerator;
use Joli\Jane\Generator\Model\GetterSetterGenerator;
use Joli\Jane\Generator\Model\PropertyGenerator;
use Joli\Jane\Generator\Naming;

use Joli\Jane\Guesser\ChainGuesserFactory;
use Joli\Jane\JsonSchemaMerger;
use Joli\Jane\Reference\Resolver;
use Symfony\Component\Serializer\SerializerInterface;

class JsonSchemaGuesserFactory
{
    public static function create(SerializerInterface $serializer, array $options = [])
    {
        $chainGuesser          = ChainGuesserFactory::create($serializer);
        $naming                = new Naming();
        $merger                = new JsonSchemaMerger();
        $resolver              = new Resolver($serializer);
        $dateFormat            = isset($options['date-format']) ? $options['date-format'] : \DateTime::RFC3339;

        $chainGuesser->addGuesser(new DateTimeGuesser($dateFormat));
        $chainGuesser->addGuesser(new SimpleTypeGuesser());
        $chainGuesser->addGuesser(new ArrayGuesser());
        $chainGuesser->addGuesser(new MultipleGuesser());
        $chainGuesser->addGuesser(new ObjectGuesser($naming, $resolver));
        $chainGuesser->addGuesser(new DefinitionGuesser());
        $chainGuesser->addGuesser(new ItemsGuesser());
        $chainGuesser->addGuesser(new AnyOfGuesser());
        $chainGuesser->addGuesser(new AllOfGuesser($resolver));
        $chainGuesser->addGuesser(new OneOfGuesser());
        $chainGuesser->addGuesser(new ObjectOneOfGuesser($merger, $resolver));
        $chainGuesser->addGuesser(new PatternPropertiesGuesser());
        $chainGuesser->addGuesser(new AdditionalItemsGuesser());
        $chainGuesser->addGuesser(new AdditionalPropertiesGuesser());

        return $chainGuesser;
    }
}
