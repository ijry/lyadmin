<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\ObjectGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class SchemaGuesser extends ObjectGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof Schema) && $object->getType() === 'object' && $object->getProperties() !== null);
    }
}
