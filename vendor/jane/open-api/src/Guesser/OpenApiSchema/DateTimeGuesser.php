<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\DateTimeGuesser as BaseDateTimeGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class DateTimeGuesser extends BaseDateTimeGuesser
{
    /**
     * {@inheritdoc}
     */
    public function supportObject($object)
    {
        return ($object instanceof Schema) && $object->getType() === 'string' && $object->getFormat() === 'date-time';
    }
}
