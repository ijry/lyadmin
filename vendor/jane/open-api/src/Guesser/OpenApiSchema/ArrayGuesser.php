<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\ArrayGuesser as BaseArrayGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class ArrayGuesser extends BaseArrayGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof Schema) && $object->getType() === 'array');
    }
}
