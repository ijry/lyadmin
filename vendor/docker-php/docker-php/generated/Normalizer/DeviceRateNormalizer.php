<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class DeviceRateNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\DeviceRate') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\DeviceRate) {
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
        $object = new \Docker\API\Model\DeviceRate();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Path')) {
            $object->setPath($data->{'Path'});
        }
        if (property_exists($data, 'Rate')) {
            $value = $data->{'Rate'};
            if (is_int($data->{'Rate'})) {
                $value = $data->{'Rate'};
            }
            if (is_string($data->{'Rate'})) {
                $value = $data->{'Rate'};
            }
            $object->setRate($value);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getPath()) {
            $data->{'Path'} = $object->getPath();
        }
        if (null !== $object->getRate()) {
            $value = $object->getRate();
            if (is_int($object->getRate())) {
                $value = $object->getRate();
            }
            if (is_string($object->getRate())) {
                $value = $object->getRate();
            }
            $data->{'Rate'} = $value;
        }

        return $data;
    }
}
