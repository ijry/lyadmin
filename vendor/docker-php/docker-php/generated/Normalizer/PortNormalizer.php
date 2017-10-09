<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class PortNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\Port') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\Port) {
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
        $object = new \Docker\API\Model\Port();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'PrivatePort')) {
            $object->setPrivatePort($data->{'PrivatePort'});
        }
        if (property_exists($data, 'PublicPort')) {
            $object->setPublicPort($data->{'PublicPort'});
        }
        if (property_exists($data, 'Type')) {
            $object->setType($data->{'Type'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getPrivatePort()) {
            $data->{'PrivatePort'} = $object->getPrivatePort();
        }
        if (null !== $object->getPublicPort()) {
            $data->{'PublicPort'} = $object->getPublicPort();
        }
        if (null !== $object->getType()) {
            $data->{'Type'} = $object->getType();
        }

        return $data;
    }
}
