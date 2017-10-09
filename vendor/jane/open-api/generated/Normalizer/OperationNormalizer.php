<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class OperationNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\Operation') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\Operation) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\Operation();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'tags')) {
            $values = [];
            foreach ($data->{'tags'} as $value) {
                $values[] = $value;
            }
            $object->setTags($values);
        }
        if (property_exists($data, 'summary')) {
            $object->setSummary($data->{'summary'});
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'externalDocs')) {
            $object->setExternalDocs($this->serializer->deserialize($data->{'externalDocs'}, 'Joli\\Jane\\OpenApi\\Model\\ExternalDocs', 'raw', $context));
        }
        if (property_exists($data, 'operationId')) {
            $object->setOperationId($data->{'operationId'});
        }
        if (property_exists($data, 'produces')) {
            $values_1 = [];
            foreach ($data->{'produces'} as $value_1) {
                $values_1[] = $value_1;
            }
            $object->setProduces($values_1);
        }
        if (property_exists($data, 'consumes')) {
            $values_2 = [];
            foreach ($data->{'consumes'} as $value_2) {
                $values_2[] = $value_2;
            }
            $object->setConsumes($values_2);
        }
        if (property_exists($data, 'parameters')) {
            $values_3 = [];
            foreach ($data->{'parameters'} as $value_3) {
                $value_4 = $value_3;
                if (is_object($value_3) and isset($value_3->{'name'}) and (isset($value_3->{'in'}) and $value_3->{'in'} == 'body') and isset($value_3->{'schema'})) {
                    $value_4 = $this->serializer->deserialize($value_3, 'Joli\\Jane\\OpenApi\\Model\\BodyParameter', 'raw', $context);
                }
                if (is_object($value_3) and (isset($value_3->{'in'}) and $value_3->{'in'} == 'header') and isset($value_3->{'name'}) and (isset($value_3->{'type'}) and ($value_3->{'type'} == 'string' or $value_3->{'type'} == 'number' or $value_3->{'type'} == 'boolean' or $value_3->{'type'} == 'integer' or $value_3->{'type'} == 'array'))) {
                    $value_4 = $this->serializer->deserialize($value_3, 'Joli\\Jane\\OpenApi\\Model\\HeaderParameterSubSchema', 'raw', $context);
                }
                if (is_object($value_3) and (isset($value_3->{'in'}) and $value_3->{'in'} == 'formData') and isset($value_3->{'name'}) and (isset($value_3->{'type'}) and ($value_3->{'type'} == 'string' or $value_3->{'type'} == 'number' or $value_3->{'type'} == 'boolean' or $value_3->{'type'} == 'integer' or $value_3->{'type'} == 'array' or $value_3->{'type'} == 'file'))) {
                    $value_4 = $this->serializer->deserialize($value_3, 'Joli\\Jane\\OpenApi\\Model\\FormDataParameterSubSchema', 'raw', $context);
                }
                if (is_object($value_3) and (isset($value_3->{'in'}) and $value_3->{'in'} == 'query') and isset($value_3->{'name'}) and (isset($value_3->{'type'}) and ($value_3->{'type'} == 'string' or $value_3->{'type'} == 'number' or $value_3->{'type'} == 'boolean' or $value_3->{'type'} == 'integer' or $value_3->{'type'} == 'array'))) {
                    $value_4 = $this->serializer->deserialize($value_3, 'Joli\\Jane\\OpenApi\\Model\\QueryParameterSubSchema', 'raw', $context);
                }
                if (is_object($value_3) and (isset($value_3->{'required'}) and $value_3->{'required'} == '1') and (isset($value_3->{'in'}) and $value_3->{'in'} == 'path') and isset($value_3->{'name'}) and (isset($value_3->{'type'}) and ($value_3->{'type'} == 'string' or $value_3->{'type'} == 'number' or $value_3->{'type'} == 'boolean' or $value_3->{'type'} == 'integer' or $value_3->{'type'} == 'array'))) {
                    $value_4 = $this->serializer->deserialize($value_3, 'Joli\\Jane\\OpenApi\\Model\\PathParameterSubSchema', 'raw', $context);
                }
                if (is_object($value_3) and isset($value_3->{'$ref'})) {
                    $value_4 = $this->serializer->deserialize($value_3, 'Joli\\Jane\\OpenApi\\Model\\JsonReference', 'raw', $context);
                }
                $values_3[] = $value_4;
            }
            $object->setParameters($values_3);
        }
        if (property_exists($data, 'responses')) {
            $values_4 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'responses'} as $key => $value_5) {
                if (preg_match('/^([0-9]{3})$|^(default)$/', $key) && isset($value_5)) {
                    $value_6 = $value_5;
                    if (is_object($value_5) and isset($value_5->{'description'})) {
                        $value_6 = $this->serializer->deserialize($value_5, 'Joli\\Jane\\OpenApi\\Model\\Response', 'raw', $context);
                    }
                    if (is_object($value_5) and isset($value_5->{'$ref'})) {
                        $value_6 = $this->serializer->deserialize($value_5, 'Joli\\Jane\\OpenApi\\Model\\JsonReference', 'raw', $context);
                    }
                    $values_4[$key] = $value_6;
                    continue;
                }
                if (preg_match('/^x-/', $key) && isset($value_5)) {
                    $values_4[$key] = $value_5;
                    continue;
                }
            }
            $object->setResponses($values_4);
        }
        if (property_exists($data, 'schemes')) {
            $values_5 = [];
            foreach ($data->{'schemes'} as $value_7) {
                $values_5[] = $value_7;
            }
            $object->setSchemes($values_5);
        }
        if (property_exists($data, 'deprecated')) {
            $object->setDeprecated($data->{'deprecated'});
        }
        if (property_exists($data, 'security')) {
            $values_6 = [];
            foreach ($data->{'security'} as $value_8) {
                $values_7 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($value_8 as $key_1 => $value_9) {
                    $values_8 = [];
                    foreach ($value_9 as $value_10) {
                        $values_8[] = $value_10;
                    }
                    $values_7[$key_1] = $values_8;
                }
                $values_6[] = $values_7;
            }
            $object->setSecurity($values_6);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getTags()) {
            $values = [];
            foreach ($object->getTags() as $value) {
                $values[] = $value;
            }
            $data->{'tags'} = $values;
        }
        if (null !== $object->getSummary()) {
            $data->{'summary'} = $object->getSummary();
        }
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getExternalDocs()) {
            $data->{'externalDocs'} = $this->serializer->serialize($object->getExternalDocs(), 'raw', $context);
        }
        if (null !== $object->getOperationId()) {
            $data->{'operationId'} = $object->getOperationId();
        }
        if (null !== $object->getProduces()) {
            $values_1 = [];
            foreach ($object->getProduces() as $value_1) {
                $values_1[] = $value_1;
            }
            $data->{'produces'} = $values_1;
        }
        if (null !== $object->getConsumes()) {
            $values_2 = [];
            foreach ($object->getConsumes() as $value_2) {
                $values_2[] = $value_2;
            }
            $data->{'consumes'} = $values_2;
        }
        if (null !== $object->getParameters()) {
            $values_3 = [];
            foreach ($object->getParameters() as $value_3) {
                $value_4 = $value_3;
                if (is_object($value_3)) {
                    $value_4 = $this->serializer->serialize($value_3, 'raw', $context);
                }
                if (is_object($value_3)) {
                    $value_4 = $this->serializer->serialize($value_3, 'raw', $context);
                }
                if (is_object($value_3)) {
                    $value_4 = $this->serializer->serialize($value_3, 'raw', $context);
                }
                if (is_object($value_3)) {
                    $value_4 = $this->serializer->serialize($value_3, 'raw', $context);
                }
                if (is_object($value_3)) {
                    $value_4 = $this->serializer->serialize($value_3, 'raw', $context);
                }
                if (is_object($value_3)) {
                    $value_4 = $this->serializer->serialize($value_3, 'raw', $context);
                }
                $values_3[] = $value_4;
            }
            $data->{'parameters'} = $values_3;
        }
        if (null !== $object->getResponses()) {
            $values_4 = new \stdClass();
            foreach ($object->getResponses() as $key => $value_5) {
                if (preg_match('/^([0-9]{3})$|^(default)$/', $key) && !is_null($value_5)) {
                    $value_6 = $value_5;
                    if (is_object($value_5)) {
                        $value_6 = $this->serializer->serialize($value_5, 'raw', $context);
                    }
                    if (is_object($value_5)) {
                        $value_6 = $this->serializer->serialize($value_5, 'raw', $context);
                    }
                    $values_4->{$key} = $value_6;
                    continue;
                }
                if (preg_match('/^x-/', $key) && !is_null($value_5)) {
                    $values_4->{$key} = $value_5;
                    continue;
                }
            }
            $data->{'responses'} = $values_4;
        }
        if (null !== $object->getSchemes()) {
            $values_5 = [];
            foreach ($object->getSchemes() as $value_7) {
                $values_5[] = $value_7;
            }
            $data->{'schemes'} = $values_5;
        }
        if (null !== $object->getDeprecated()) {
            $data->{'deprecated'} = $object->getDeprecated();
        }
        if (null !== $object->getSecurity()) {
            $values_6 = [];
            foreach ($object->getSecurity() as $value_8) {
                $values_7 = new \stdClass();
                foreach ($value_8 as $key_1 => $value_9) {
                    $values_8 = [];
                    foreach ($value_9 as $value_10) {
                        $values_8[] = $value_10;
                    }
                    $values_7->{$key_1} = $values_8;
                }
                $values_6[] = $values_7;
            }
            $data->{'security'} = $values_6;
        }

        return $data;
    }
}
