<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class DeviceNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\Device') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\Device) {
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
        $object = new \Docker\API\Model\Device();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'PathOnHost')) {
            $object->setPathOnHost($data->{'PathOnHost'});
        }
        if (property_exists($data, 'PathInContainer')) {
            $object->setPathInContainer($data->{'PathInContainer'});
        }
        if (property_exists($data, 'CgroupPermissions')) {
            $object->setCgroupPermissions($data->{'CgroupPermissions'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getPathOnHost()) {
            $data->{'PathOnHost'} = $object->getPathOnHost();
        }
        if (null !== $object->getPathInContainer()) {
            $data->{'PathInContainer'} = $object->getPathInContainer();
        }
        if (null !== $object->getCgroupPermissions()) {
            $data->{'CgroupPermissions'} = $object->getCgroupPermissions();
        }

        return $data;
    }
}
