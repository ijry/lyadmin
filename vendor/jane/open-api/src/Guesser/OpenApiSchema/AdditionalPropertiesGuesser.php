<?php

namespace Joli\Jane\OpenApi\Guesser\OpenApiSchema;

use Joli\Jane\Guesser\JsonSchema\AdditionalPropertiesGuesser as BaseAdditionalPropertiesGuesser;
use Joli\Jane\OpenApi\Model\Schema;

class AdditionalPropertiesGuesser extends BaseAdditionalPropertiesGuesser
{
    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        if (!($object instanceof Schema)) {
            return false;
        }

        if ($object->getType() !== 'object') {
            return false;
        }

        if ($object->getAdditionalProperties() !== true && !is_object($object->getAdditionalProperties())) {
            return false;
        }

        return true;
    }
}
