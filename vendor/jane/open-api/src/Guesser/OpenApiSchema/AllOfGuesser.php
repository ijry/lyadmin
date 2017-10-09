<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\AllOfGuesser as BaseAllOfGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class AllOfGuesser extends BaseAllOfGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof Schema) && is_array($object->getAllOf()) && count($object->getAllOf()) > 0);
    }
}
