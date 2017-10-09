<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class BodyParameterNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\BodyParameter') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\BodyParameter) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\BodyParameter();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'name')) {
            $object->setName($data->{'name'});
        }
        if (property_exists($data, 'in')) {
            $object->setIn($data->{'in'});
        }
        if (property_exists($data, 'required')) {
            $object->setRequired($data->{'required'});
        }
        if (property_exists($data, 'schema')) {
            $object->setSchema($this->serializer->deserialize($data->{'schema'}, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getName()) {
            $data->{'name'} = $object->getName();
        }
        if (null !== $object->getIn()) {
            $data->{'in'} = $object->getIn();
        }
        if (null !== $object->getRequired()) {
            $data->{'required'} = $object->getRequired();
        }
        if (null !== $object->getSchema()) {
            $data->{'schema'} = $this->serializer->serialize($object->getSchema(), 'raw', $context);
        }

        return $data;
    }
}
