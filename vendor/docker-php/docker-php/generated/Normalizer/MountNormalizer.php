<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class MountNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\Mount') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\Mount) {
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
        $object = new \Docker\API\Model\Mount();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Name')) {
            $object->setName($data->{'Name'});
        }
        if (property_exists($data, 'Source')) {
            $object->setSource($data->{'Source'});
        }
        if (property_exists($data, 'Destination')) {
            $object->setDestination($data->{'Destination'});
        }
        if (property_exists($data, 'Driver')) {
            $object->setDriver($data->{'Driver'});
        }
        if (property_exists($data, 'Mode')) {
            $object->setMode($data->{'Mode'});
        }
        if (property_exists($data, 'RW')) {
            $object->setRW($data->{'RW'});
        }
        if (property_exists($data, 'Propagation')) {
            $object->setPropagation($data->{'Propagation'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getName()) {
            $data->{'Name'} = $object->getName();
        }
        if (null !== $object->getSource()) {
            $data->{'Source'} = $object->getSource();
        }
        if (null !== $object->getDestination()) {
            $data->{'Destination'} = $object->getDestination();
        }
        if (null !== $object->getDriver()) {
            $data->{'Driver'} = $object->getDriver();
        }
        if (null !== $object->getMode()) {
            $data->{'Mode'} = $object->getMode();
        }
        if (null !== $object->getRW()) {
            $data->{'RW'} = $object->getRW();
        }
        if (null !== $object->getPropagation()) {
            $data->{'Propagation'} = $object->getPropagation();
        }

        return $data;
    }
}
