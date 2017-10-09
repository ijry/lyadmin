<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ResponseNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\Response') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\Response) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\Response();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'schema')) {
            $value = $data->{'schema'};
            if (is_object($data->{'schema'})) {
                $value = $this->serializer->deserialize($data->{'schema'}, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context);
            }
            if (is_object($data->{'schema'}) and (isset($data->{'schema'}->{'type'}) and $data->{'schema'}->{'type'} == 'file')) {
                $value = $this->serializer->deserialize($data->{'schema'}, 'Joli\\Jane\\OpenApi\\Model\\FileSchema', 'raw', $context);
            }
            $object->setSchema($value);
        }
        if (property_exists($data, 'headers')) {
            $values = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'headers'} as $key => $value_1) {
                $values[$key] = $this->serializer->deserialize($value_1, 'Joli\\Jane\\OpenApi\\Model\\Header', 'raw', $context);
            }
            $object->setHeaders($values);
        }
        if (property_exists($data, 'examples')) {
            $values_1 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'examples'} as $key_1 => $value_2) {
                $values_1[$key_1] = $value_2;
            }
            $object->setExamples($values_1);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getSchema()) {
            $value = $object->getSchema();
            if (is_object($object->getSchema())) {
                $value = $this->serializer->serialize($object->getSchema(), 'raw', $context);
            }
            if (is_object($object->getSchema())) {
                $value = $this->serializer->serialize($object->getSchema(), 'raw', $context);
            }
            $data->{'schema'} = $value;
        }
        if (null !== $object->getHeaders()) {
            $values = new \stdClass();
            foreach ($object->getHeaders() as $key => $value_1) {
                $values->{$key} = $this->serializer->serialize($value_1, 'raw', $context);
            }
            $data->{'headers'} = $values;
        }
        if (null !== $object->getExamples()) {
            $values_1 = new \stdClass();
            foreach ($object->getExamples() as $key_1 => $value_2) {
                $values_1->{$key_1} = $value_2;
            }
            $data->{'examples'} = $values_1;
        }

        return $data;
    }
}
