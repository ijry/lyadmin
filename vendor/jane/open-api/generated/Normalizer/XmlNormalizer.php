<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class XmlNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\Xml') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\Xml) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\Xml();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'name')) {
            $object->setName($data->{'name'});
        }
        if (property_exists($data, 'namespace')) {
            $object->setNamespace($data->{'namespace'});
        }
        if (property_exists($data, 'prefix')) {
            $object->setPrefix($data->{'prefix'});
        }
        if (property_exists($data, 'attribute')) {
            $object->setAttribute($data->{'attribute'});
        }
        if (property_exists($data, 'wrapped')) {
            $object->setWrapped($data->{'wrapped'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getName()) {
            $data->{'name'} = $object->getName();
        }
        if (null !== $object->getNamespace()) {
            $data->{'namespace'} = $object->getNamespace();
        }
        if (null !== $object->getPrefix()) {
            $data->{'prefix'} = $object->getPrefix();
        }
        if (null !== $object->getAttribute()) {
            $data->{'attribute'} = $object->getAttribute();
        }
        if (null !== $object->getWrapped()) {
            $data->{'wrapped'} = $object->getWrapped();
        }

        return $data;
    }
}
