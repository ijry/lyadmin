<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class NetworkingConfigNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\NetworkingConfig') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\NetworkingConfig) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (empty($data)) {
            return null;
        }
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Docker\API\Model\NetworkingConfig();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'EndpointsConfig')) {
            $values = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'EndpointsConfig'} as $key => $value) {
                $values[$key] = $this->serializer->deserialize($value, 'Docker\\API\\Model\\EndpointSettings', 'raw', $context);
            }
            $object->setEndpointsConfig($values);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getEndpointsConfig()) {
            $values = new \stdClass();
            foreach ($object->getEndpointsConfig() as $key => $value) {
                $values->{$key} = $this->serializer->serialize($value, 'raw', $context);
            }
            $data->{'EndpointsConfig'} = $values;
        }

        return $data;
    }
}
