<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class VolumeListNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\VolumeList') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\VolumeList) {
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
        $object = new \Docker\API\Model\VolumeList();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Volumes')) {
            $value = $data->{'Volumes'};
            if (is_array($data->{'Volumes'})) {
                $values = [];
                foreach ($data->{'Volumes'} as $value_1) {
                    $values[] = $this->serializer->deserialize($value_1, 'Docker\\API\\Model\\Volume', 'raw', $context);
                }
                $value = $values;
            }
            if (is_null($data->{'Volumes'})) {
                $value = $data->{'Volumes'};
            }
            $object->setVolumes($value);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data  = new \stdClass();
        $value = $object->getVolumes();
        if (is_array($object->getVolumes())) {
            $values = [];
            foreach ($object->getVolumes() as $value_1) {
                $values[] = $this->serializer->serialize($value_1, 'raw', $context);
            }
            $value = $values;
        }
        if (is_null($object->getVolumes())) {
            $value = $object->getVolumes();
        }
        $data->{'Volumes'} = $value;

        return $data;
    }
}
