<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class PathItemNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\PathItem') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\PathItem) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\PathItem();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, '$ref')) {
            $object->setDollarRef($data->{'$ref'});
        }
        if (property_exists($data, 'get')) {
            $object->setGet($this->serializer->deserialize($data->{'get'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'put')) {
            $object->setPut($this->serializer->deserialize($data->{'put'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'post')) {
            $object->setPost($this->serializer->deserialize($data->{'post'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'delete')) {
            $object->setDelete($this->serializer->deserialize($data->{'delete'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'options')) {
            $object->setOptions($this->serializer->deserialize($data->{'options'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'head')) {
            $object->setHead($this->serializer->deserialize($data->{'head'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'patch')) {
            $object->setPatch($this->serializer->deserialize($data->{'patch'}, 'Joli\\Jane\\OpenApi\\Model\\Operation', 'raw', $context));
        }
        if (property_exists($data, 'parameters')) {
            $values = [];
            foreach ($data->{'parameters'} as $value) {
                $value_1 = $value;
                if (is_object($value) and isset($value->{'name'}) and (isset($value->{'in'}) and $value->{'in'} == 'body') and isset($value->{'schema'})) {
                    $value_1 = $this->serializer->deserialize($value, 'Joli\\Jane\\OpenApi\\Model\\BodyParameter', 'raw', $context);
                }
                if (is_object($value) and (isset($value->{'in'}) and $value->{'in'} == 'header') and isset($value->{'name'}) and (isset($value->{'type'}) and ($value->{'type'} == 'string' or $value->{'type'} == 'number' or $value->{'type'} == 'boolean' or $value->{'type'} == 'integer' or $value->{'type'} == 'array'))) {
                    $value_1 = $this->serializer->deserialize($value, 'Joli\\Jane\\OpenApi\\Model\\HeaderParameterSubSchema', 'raw', $context);
                }
                if (is_object($value) and (isset($value->{'in'}) and $value->{'in'} == 'formData') and isset($value->{'name'}) and (isset($value->{'type'}) and ($value->{'type'} == 'string' or $value->{'type'} == 'number' or $value->{'type'} == 'boolean' or $value->{'type'} == 'integer' or $value->{'type'} == 'array' or $value->{'type'} == 'file'))) {
                    $value_1 = $this->serializer->deserialize($value, 'Joli\\Jane\\OpenApi\\Model\\FormDataParameterSubSchema', 'raw', $context);
                }
                if (is_object($value) and (isset($value->{'in'}) and $value->{'in'} == 'query') and isset($value->{'name'}) and (isset($value->{'type'}) and ($value->{'type'} == 'string' or $value->{'type'} == 'number' or $value->{'type'} == 'boolean' or $value->{'type'} == 'integer' or $value->{'type'} == 'array'))) {
                    $value_1 = $this->serializer->deserialize($value, 'Joli\\Jane\\OpenApi\\Model\\QueryParameterSubSchema', 'raw', $context);
                }
                if (is_object($value) and (isset($value->{'required'}) and $value->{'required'} == '1') and (isset($value->{'in'}) and $value->{'in'} == 'path') and isset($value->{'name'}) and (isset($value->{'type'}) and ($value->{'type'} == 'string' or $value->{'type'} == 'number' or $value->{'type'} == 'boolean' or $value->{'type'} == 'integer' or $value->{'type'} == 'array'))) {
                    $value_1 = $this->serializer->deserialize($value, 'Joli\\Jane\\OpenApi\\Model\\PathParameterSubSchema', 'raw', $context);
                }
                if (is_object($value) and isset($value->{'$ref'})) {
                    $value_1 = $this->serializer->deserialize($value, 'Joli\\Jane\\OpenApi\\Model\\JsonReference', 'raw', $context);
                }
                $values[] = $value_1;
            }
            $object->setParameters($values);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getDollarRef()) {
            $data->{'$ref'} = $object->getDollarRef();
        }
        if (null !== $object->getGet()) {
            $data->{'get'} = $this->serializer->serialize($object->getGet(), 'raw', $context);
        }
        if (null !== $object->getPut()) {
            $data->{'put'} = $this->serializer->serialize($object->getPut(), 'raw', $context);
        }
        if (null !== $object->getPost()) {
            $data->{'post'} = $this->serializer->serialize($object->getPost(), 'raw', $context);
        }
        if (null !== $object->getDelete()) {
            $data->{'delete'} = $this->serializer->serialize($object->getDelete(), 'raw', $context);
        }
        if (null !== $object->getOptions()) {
            $data->{'options'} = $this->serializer->serialize($object->getOptions(), 'raw', $context);
        }
        if (null !== $object->getHead()) {
            $data->{'head'} = $this->serializer->serialize($object->getHead(), 'raw', $context);
        }
        if (null !== $object->getPatch()) {
            $data->{'patch'} = $this->serializer->serialize($object->getPatch(), 'raw', $context);
        }
        if (null !== $object->getParameters()) {
            $values = [];
            foreach ($object->getParameters() as $value) {
                $value_1 = $value;
                if (is_object($value)) {
                    $value_1 = $this->serializer->serialize($value, 'raw', $context);
                }
                if (is_object($value)) {
                    $value_1 = $this->serializer->serialize($value, 'raw', $context);
                }
                if (is_object($value)) {
                    $value_1 = $this->serializer->serialize($value, 'raw', $context);
                }
                if (is_object($value)) {
                    $value_1 = $this->serializer->serialize($value, 'raw', $context);
                }
                if (is_object($value)) {
                    $value_1 = $this->serializer->serialize($value, 'raw', $context);
                }
                if (is_object($value)) {
                    $value_1 = $this->serializer->serialize($value, 'raw', $context);
                }
                $values[] = $value_1;
            }
            $data->{'parameters'} = $values;
        }

        return $data;
    }
}
