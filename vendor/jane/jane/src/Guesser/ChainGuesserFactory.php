<?php

namespace Joli\Jane\Guesser;

use Joli\Jane\Reference\Resolver;
use Symfony\Component\Serializer\SerializerInterface;

class ChainGuesserFactory
{
    public static function create(SerializerInterface $serializer)
    {
        $chainGuesser = new ChainGuesser();
        $chainGuesser->addGuesser(new ReferenceGuesser(new Resolver($serializer)));

        return $chainGuesser;
    }
}
 