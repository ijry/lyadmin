<?php

namespace Joli\Jane\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class JsonSchemaNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\Model\\JsonSchema') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\Model\JsonSchema) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\Model\JsonSchema();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'id')) {
            $object->setId($data->{'id'});
        }
        if (property_exists($data, '$schema')) {
            $object->setDollarSchema($data->{'$schema'});
        }
        if (property_exists($data, 'title')) {
            $object->setTitle($data->{'title'});
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'default')) {
            $object->setDefault($data->{'default'});
        }
        if (property_exists($data, 'multipleOf')) {
            $object->setMultipleOf($data->{'multipleOf'});
        }
        if (property_exists($data, 'maximum')) {
            $object->setMaximum($data->{'maximum'});
        }
        if (property_exists($data, 'exclusiveMaximum')) {
            $object->setExclusiveMaximum($data->{'exclusiveMaximum'});
        }
        if (property_exists($data, 'minimum')) {
            $object->setMinimum($data->{'minimum'});
        }
        if (property_exists($data, 'exclusiveMinimum')) {
            $object->setExclusiveMinimum($data->{'exclusiveMinimum'});
        }
        if (property_exists($data, 'maxLength')) {
            $object->setMaxLength($data->{'maxLength'});
        }
        if (property_exists($data, 'minLength')) {
            $object->setMinLength($data->{'minLength'});
        }
        if (property_exists($data, 'pattern')) {
            $object->setPattern($data->{'pattern'});
        }
        if (property_exists($data, 'additionalItems')) {
            $value = $data->{'additionalItems'};
            if (is_bool($data->{'additionalItems'})) {
                $value = $data->{'additionalItems'};
            }
            if (is_object($data->{'additionalItems'})) {
                $value = $this->serializer->deserialize($data->{'additionalItems'}, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setAdditionalItems($value);
        }
        if (property_exists($data, 'items')) {
            $value_1 = $data->{'items'};
            if (is_object($data->{'items'})) {
                $value_1 = $this->serializer->deserialize($data->{'items'}, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            if (is_array($data->{'items'})) {
                $values = [];
                foreach ($data->{'items'} as $value_2) {
                    $values[] = $this->serializer->deserialize($value_2, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
                }
                $value_1 = $values;
            }
            $object->setItems($value_1);
        }
        if (property_exists($data, 'maxItems')) {
            $object->setMaxItems($data->{'maxItems'});
        }
        if (property_exists($data, 'minItems')) {
            $object->setMinItems($data->{'minItems'});
        }
        if (property_exists($data, 'uniqueItems')) {
            $object->setUniqueItems($data->{'uniqueItems'});
        }
        if (property_exists($data, 'maxProperties')) {
            $object->setMaxProperties($data->{'maxProperties'});
        }
        if (property_exists($data, 'minProperties')) {
            $object->setMinProperties($data->{'minProperties'});
        }
        if (property_exists($data, 'required')) {
            $values_1 = [];
            foreach ($data->{'required'} as $value_3) {
                $values_1[] = $value_3;
            }
            $object->setRequired($values_1);
        }
        if (property_exists($data, 'additionalProperties')) {
            $value_4 = $data->{'additionalProperties'};
            if (is_bool($data->{'additionalProperties'})) {
                $value_4 = $data->{'additionalProperties'};
            }
            if (is_object($data->{'additionalProperties'})) {
                $value_4 = $this->serializer->deserialize($data->{'additionalProperties'}, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setAdditionalProperties($value_4);
        }
        if (property_exists($data, 'definitions')) {
            $values_2 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'definitions'} as $key => $value_5) {
                $values_2[$key] = $this->serializer->deserialize($value_5, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setDefinitions($values_2);
        }
        if (property_exists($data, 'properties')) {
            $values_3 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'properties'} as $key_1 => $value_6) {
                $values_3[$key_1] = $this->serializer->deserialize($value_6, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setProperties($values_3);
        }
        if (property_exists($data, 'patternProperties')) {
            $values_4 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'patternProperties'} as $key_2 => $value_7) {
                $values_4[$key_2] = $this->serializer->deserialize($value_7, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setPatternProperties($values_4);
        }
        if (property_exists($data, 'dependencies')) {
            $values_5 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'dependencies'} as $key_3 => $value_8) {
                $value_9 = $value_8;
                if (is_object($value_8)) {
                    $value_9 = $this->serializer->deserialize($value_8, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
                }
                if (is_array($value_8)) {
                    $values_6 = [];
                    foreach ($value_8 as $value_10) {
                        $values_6[] = $value_10;
                    }
                    $value_9 = $values_6;
                }
                $values_5[$key_3] = $value_9;
            }
            $object->setDependencies($values_5);
        }
        if (property_exists($data, 'enum')) {
            $values_7 = [];
            foreach ($data->{'enum'} as $value_11) {
                $values_7[] = $value_11;
            }
            $object->setEnum($values_7);
        }
        if (property_exists($data, 'type')) {
            $value_12 = $data->{'type'};
            if (isset($data->{'type'})) {
                $value_12 = $data->{'type'};
            }
            if (is_array($data->{'type'})) {
                $values_8 = [];
                foreach ($data->{'type'} as $value_13) {
                    $values_8[] = $value_13;
                }
                $value_12 = $values_8;
            }
            $object->setType($value_12);
        }
        if (property_exists($data, 'format')) {
            $object->setFormat($data->{'format'});
        }
        if (property_exists($data, 'allOf')) {
            $values_9 = [];
            foreach ($data->{'allOf'} as $value_14) {
                $values_9[] = $this->serializer->deserialize($value_14, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setAllOf($values_9);
        }
        if (property_exists($data, 'anyOf')) {
            $values_10 = [];
            foreach ($data->{'anyOf'} as $value_15) {
                $values_10[] = $this->serializer->deserialize($value_15, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setAnyOf($values_10);
        }
        if (property_exists($data, 'oneOf')) {
            $values_11 = [];
            foreach ($data->{'oneOf'} as $value_16) {
                $values_11[] = $this->serializer->deserialize($value_16, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context);
            }
            $object->setOneOf($values_11);
        }
        if (property_exists($data, 'not')) {
            $object->setNot($this->serializer->deserialize($data->{'not'}, 'Joli\\Jane\\Model\\JsonSchema', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getId()) {
            $data->{'id'} = $object->getId();
        }
        if (null !== $object->getDollarSchema()) {
            $data->{'$schema'} = $object->getDollarSchema();
        }
        if (null !== $object->getTitle()) {
            $data->{'title'} = $object->getTitle();
        }
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getDefault()) {
            $data->{'default'} = $object->getDefault();
        }
        if (null !== $object->getMultipleOf()) {
            $data->{'multipleOf'} = $object->getMultipleOf();
        }
        if (null !== $object->getMaximum()) {
            $data->{'maximum'} = $object->getMaximum();
        }
        if (null !== $object->getExclusiveMaximum()) {
            $data->{'exclusiveMaximum'} = $object->getExclusiveMaximum();
        }
        if (null !== $object->getMinimum()) {
            $data->{'minimum'} = $object->getMinimum();
        }
        if (null !== $object->getExclusiveMinimum()) {
            $data->{'exclusiveMinimum'} = $object->getExclusiveMinimum();
        }
        if (null !== $object->getMaxLength()) {
            $data->{'maxLength'} = $object->getMaxLength();
        }
        if (null !== $object->getMinLength()) {
            $data->{'minLength'} = $object->getMinLength();
        }
        if (null !== $object->getPattern()) {
            $data->{'pattern'} = $object->getPattern();
        }
        if (null !== $object->getAdditionalItems()) {
            $value = $object->getAdditionalItems();
            if (is_bool($object->getAdditionalItems())) {
                $value = $object->getAdditionalItems();
            }
            if (is_object($object->getAdditionalItems())) {
                $value = $this->serializer->serialize($object->getAdditionalItems(), 'raw', $context);
            }
            $data->{'additionalItems'} = $value;
        }
        if (null !== $object->getItems()) {
            $value_1 = $object->getItems();
            if (is_object($object->getItems())) {
                $value_1 = $this->serializer->serialize($object->getItems(), 'raw', $context);
            }
            if (is_array($object->getItems())) {
                $values = [];
                foreach ($object->getItems() as $value_2) {
                    $values[] = $this->serializer->serialize($value_2, 'raw', $context);
                }
                $value_1 = $values;
            }
            $data->{'items'} = $value_1;
        }
        if (null !== $object->getMaxItems()) {
            $data->{'maxItems'} = $object->getMaxItems();
        }
        if (null !== $object->getMinItems()) {
            $data->{'minItems'} = $object->getMinItems();
        }
        if (null !== $object->getUniqueItems()) {
            $data->{'uniqueItems'} = $object->getUniqueItems();
        }
        if (null !== $object->getMaxProperties()) {
            $data->{'maxProperties'} = $object->getMaxProperties();
        }
        if (null !== $object->getMinProperties()) {
            $data->{'minProperties'} = $object->getMinProperties();
        }
        if (null !== $object->getRequired()) {
            $values_1 = [];
            foreach ($object->getRequired() as $value_3) {
                $values_1[] = $value_3;
            }
            $data->{'required'} = $values_1;
        }
        if (null !== $object->getAdditionalProperties()) {
            $value_4 = $object->getAdditionalProperties();
            if (is_bool($object->getAdditionalProperties())) {
                $value_4 = $object->getAdditionalProperties();
            }
            if (is_object($object->getAdditionalProperties())) {
                $value_4 = $this->serializer->serialize($object->getAdditionalProperties(), 'raw', $context);
            }
            $data->{'additionalProperties'} = $value_4;
        }
        if (null !== $object->getDefinitions()) {
            $values_2 = new \stdClass();
            foreach ($object->getDefinitions() as $key => $value_5) {
                $values_2->{$key} = $this->serializer->serialize($value_5, 'raw', $context);
            }
            $data->{'definitions'} = $values_2;
        }
        if (null !== $object->getProperties()) {
            $values_3 = new \stdClass();
            foreach ($object->getProperties() as $key_1 => $value_6) {
                $values_3->{$key_1} = $this->serializer->serialize($value_6, 'raw', $context);
            }
            $data->{'properties'} = $values_3;
        }
        if (null !== $object->getPatternProperties()) {
            $values_4 = new \stdClass();
            foreach ($object->getPatternProperties() as $key_2 => $value_7) {
                $values_4->{$key_2} = $this->serializer->serialize($value_7, 'raw', $context);
            }
            $data->{'patternProperties'} = $values_4;
        }
        if (null !== $object->getDependencies()) {
            $values_5 = new \stdClass();
            foreach ($object->getDependencies() as $key_3 => $value_8) {
                $value_9 = $value_8;
                if (is_object($value_8)) {
                    $value_9 = $this->serializer->serialize($value_8, 'raw', $context);
                }
                if (is_array($value_8)) {
                    $values_6 = [];
                    foreach ($value_8 as $value_10) {
                        $values_6[] = $value_10;
                    }
                    $value_9 = $values_6;
                }
                $values_5->{$key_3} = $value_9;
            }
            $data->{'dependencies'} = $values_5;
        }
        if (null !== $object->getEnum()) {
            $values_7 = [];
            foreach ($object->getEnum() as $value_11) {
                $values_7[] = $value_11;
            }
            $data->{'enum'} = $values_7;
        }
        if (null !== $object->getType()) {
            $value_12 = $object->getType();
            if (!is_null($object->getType())) {
                $value_12 = $object->getType();
            }
            if (is_array($object->getType())) {
                $values_8 = [];
                foreach ($object->getType() as $value_13) {
                    $values_8[] = $value_13;
                }
                $value_12 = $values_8;
            }
            $data->{'type'} = $value_12;
        }
        if (null !== $object->getFormat()) {
            $data->{'format'} = $object->getFormat();
        }
        if (null !== $object->getAllOf()) {
            $values_9 = [];
            foreach ($object->getAllOf() as $value_14) {
                $values_9[] = $this->serializer->serialize($value_14, 'raw', $context);
            }
            $data->{'allOf'} = $values_9;
        }
        if (null !== $object->getAnyOf()) {
            $values_10 = [];
            foreach ($object->getAnyOf() as $value_15) {
                $values_10[] = $this->serializer->serialize($value_15, 'raw', $context);
            }
            $data->{'anyOf'} = $values_10;
        }
        if (null !== $object->getOneOf()) {
            $values_11 = [];
            foreach ($object->getOneOf() as $value_16) {
                $values_11[] = $this->serializer->serialize($value_16, 'raw', $context);
            }
            $data->{'oneOf'} = $values_11;
        }
        if (null !== $object->getNot()) {
            $data->{'not'} = $this->serializer->serialize($object->getNot(), 'raw', $context);
        }

        return $data;
    }
}
