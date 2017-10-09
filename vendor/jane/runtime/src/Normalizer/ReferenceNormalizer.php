<?php

namespace Joli\Jane\Runtime\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReferenceNormalizer implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $ref = new \stdClass();
        $ref->{'$ref'} = $object->getReference();

        return $ref;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof Reference);
    }

}
