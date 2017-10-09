<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Generator\Naming;
use Joli\Jane\Guesser\ChainGuesser;
use Joli\Jane\Guesser\ReferenceGuesser;
use Joli\Jane\Reference\Resolver;
use Symfony\Component\Serializer\SerializerInterface;

class GuesserFactory
{
    public static function create(SerializerInterface $serializer, array $options = [])
    {
        $naming = new Naming();
        $resolver = new Resolver($serializer);
        $dateFormat = isset($options['date-format']) ? $options['date-format'] : \DateTime::RFC3339;

        $chainGuesser = new ChainGuesser();
        $chainGuesser->addGuesser(new DateTimeGuesser($dateFormat));
        $chainGuesser->addGuesser(new ReferenceGuesser($resolver));
        $chainGuesser->addGuesser(new OpenApiGuesser());
        $chainGuesser->addGuesser(new SchemaGuesser($naming, $resolver));
        $chainGuesser->addGuesser(new AdditionalPropertiesGuesser());
        $chainGuesser->addGuesser(new AllOfGuesser($resolver));
        $chainGuesser->addGuesser(new ArrayGuesser());
        $chainGuesser->addGuesser(new ItemsGuesser());
        $chainGuesser->addGuesser(new SimpleTypeGuesser());
        $chainGuesser->addGuesser(new MultipleGuesser());

        return $chainGuesser;
    }
}
