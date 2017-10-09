<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\MultipleGuesser as BaseMultipleGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class MultipleGuesser extends BaseMultipleGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return ($object instanceof Schema) && is_array($object->getType());
    }
}
