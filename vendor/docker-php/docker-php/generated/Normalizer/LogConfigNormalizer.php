<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class LogConfigNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\LogConfig') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\LogConfig) {
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
        $object = new \Docker\API\Model\LogConfig();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Type')) {
            $object->setType($data->{'Type'});
        }
        if (property_exists($data, 'Config')) {
            $value = $data->{'Config'};
            if (is_object($data->{'Config'})) {
                $values = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($data->{'Config'} as $key => $value_1) {
                    $values[$key] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'Config'})) {
                $value = $data->{'Config'};
            }
            $object->setConfig($value);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getType()) {
            $data->{'Type'} = $object->getType();
        }
        $value = $object->getConfig();
        if (is_object($object->getConfig())) {
            $values = new \stdClass();
            foreach ($object->getConfig() as $key => $value_1) {
                $values->{$key} = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getConfig())) {
            $value = $object->getConfig();
        }
        $data->{'Config'} = $value;

        return $data;
    }
}
