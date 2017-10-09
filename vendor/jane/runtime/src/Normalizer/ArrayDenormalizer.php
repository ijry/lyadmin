<?php

namespace Joli\Jane\Runtime\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

/**
 * Allow to denormalize value of type "MyClass[]" if it's possible to denormalize value of type "MyClass"
 */
class ArrayDenormalizer extends SerializerAwareNormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $serializer = $this->serializer;
        $class = substr($class, 0, -2);

        return array_map(
            function ($data) use ($serializer, $class, $format, $context) {
                return $serializer->denormalize($data, $class, $format, $context);
            },
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        if (!($this->serializer instanceof DenormalizerInterface)) {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        return substr($type, -2) === '[]' && $this->serializer->supportsDenormalization($data, substr($type, 0, -2), $format);
    }
}
