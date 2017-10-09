<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class SchemaNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\Schema') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\Schema) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\Schema();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, '$ref')) {
            $object->setDollarRef($data->{'$ref'});
        }
        if (property_exists($data, 'format')) {
            $object->setFormat($data->{'format'});
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
            $values = [];
            foreach ($data->{'required'} as $value) {
                $values[] = $value;
            }
            $object->setRequired($values);
        }
        if (property_exists($data, 'enum')) {
            $values_1 = [];
            foreach ($data->{'enum'} as $value_1) {
                $values_1[] = $value_1;
            }
            $object->setEnum($values_1);
        }
        if (property_exists($data, 'additionalProperties')) {
            $value_2 = $data->{'additionalProperties'};
            if (is_object($data->{'additionalProperties'})) {
                $value_2 = $this->serializer->deserialize($data->{'additionalProperties'}, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context);
            }
            if (is_bool($data->{'additionalProperties'})) {
                $value_2 = $data->{'additionalProperties'};
            }
            $object->setAdditionalProperties($value_2);
        }
        if (property_exists($data, 'type')) {
            $value_3 = $data->{'type'};
            if (isset($data->{'type'})) {
                $value_3 = $data->{'type'};
            }
            if (is_array($data->{'type'})) {
                $values_2 = [];
                foreach ($data->{'type'} as $value_4) {
                    $values_2[] = $value_4;
                }
                $value_3 = $values_2;
            }
            $object->setType($value_3);
        }
        if (property_exists($data, 'items')) {
            $value_5 = $data->{'items'};
            if (is_object($data->{'items'})) {
                $value_5 = $this->serializer->deserialize($data->{'items'}, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context);
            }
            if (is_array($data->{'items'})) {
                $values_3 = [];
                foreach ($data->{'items'} as $value_6) {
                    $values_3[] = $this->serializer->deserialize($value_6, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context);
                }
                $value_5 = $values_3;
            }
            $object->setItems($value_5);
        }
        if (property_exists($data, 'allOf')) {
            $values_4 = [];
            foreach ($data->{'allOf'} as $value_7) {
                $values_4[] = $this->serializer->deserialize($value_7, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context);
            }
            $object->setAllOf($values_4);
        }
        if (property_exists($data, 'properties')) {
            $values_5 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'properties'} as $key => $value_8) {
                $values_5[$key] = $this->serializer->deserialize($value_8, 'Joli\\Jane\\OpenApi\\Model\\Schema', 'raw', $context);
            }
            $object->setProperties($values_5);
        }
        if (property_exists($data, 'discriminator')) {
            $object->setDiscriminator($data->{'discriminator'});
        }
        if (property_exists($data, 'readOnly')) {
            $object->setReadOnly($data->{'readOnly'});
        }
        if (property_exists($data, 'xml')) {
            $object->setXml($this->serializer->deserialize($data->{'xml'}, 'Joli\\Jane\\OpenApi\\Model\\Xml', 'raw', $context));
        }
        if (property_exists($data, 'externalDocs')) {
            $object->setExternalDocs($this->serializer->deserialize($data->{'externalDocs'}, 'Joli\\Jane\\OpenApi\\Model\\ExternalDocs', 'raw', $context));
        }
        if (property_exists($data, 'example')) {
            $object->setExample($data->{'example'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getDollarRef()) {
            $data->{'$ref'} = $object->getDollarRef();
        }
        if (null !== $object->getFormat()) {
            $data->{'format'} = $object->getFormat();
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
            $values = [];
            foreach ($object->getRequired() as $value) {
                $values[] = $value;
            }
            $data->{'required'} = $values;
        }
        if (null !== $object->getEnum()) {
            $values_1 = [];
            foreach ($object->getEnum() as $value_1) {
                $values_1[] = $value_1;
            }
            $data->{'enum'} = $values_1;
        }
        if (null !== $object->getAdditionalProperties()) {
            $value_2 = $object->getAdditionalProperties();
            if (is_object($object->getAdditionalProperties())) {
                $value_2 = $this->serializer->serialize($object->getAdditionalProperties(), 'raw', $context);
            }
            if (is_bool($object->getAdditionalProperties())) {
                $value_2 = $object->getAdditionalProperties();
            }
            $data->{'additionalProperties'} = $value_2;
        }
        if (null !== $object->getType()) {
            $value_3 = $object->getType();
            if (!is_null($object->getType())) {
                $value_3 = $object->getType();
            }
            if (is_array($object->getType())) {
                $values_2 = [];
                foreach ($object->getType() as $value_4) {
                    $values_2[] = $value_4;
                }
                $value_3 = $values_2;
            }
            $data->{'type'} = $value_3;
        }
        if (null !== $object->getItems()) {
            $value_5 = $object->getItems();
            if (is_object($object->getItems())) {
                $value_5 = $this->serializer->serialize($object->getItems(), 'raw', $context);
            }
            if (is_array($object->getItems())) {
                $values_3 = [];
                foreach ($object->getItems() as $value_6) {
                    $values_3[] = $this->serializer->serialize($value_6, 'raw', $context);
                }
                $value_5 = $values_3;
            }
            $data->{'items'} = $value_5;
        }
        if (null !== $object->getAllOf()) {
            $values_4 = [];
            foreach ($object->getAllOf() as $value_7) {
                $values_4[] = $this->serializer->serialize($value_7, 'raw', $context);
            }
            $data->{'allOf'} = $values_4;
        }
        if (null !== $object->getProperties()) {
            $values_5 = new \stdClass();
            foreach ($object->getProperties() as $key => $value_8) {
                $values_5->{$key} = $this->serializer->serialize($value_8, 'raw', $context);
            }
            $data->{'properties'} = $values_5;
        }
        if (null !== $object->getDiscriminator()) {
            $data->{'discriminator'} = $object->getDiscriminator();
        }
        if (null !== $object->getReadOnly()) {
            $data->{'readOnly'} = $object->getReadOnly();
        }
        if (null !== $object->getXml()) {
            $data->{'xml'} = $this->serializer->serialize($object->getXml(), 'raw', $context);
        }
        if (null !== $object->getExternalDocs()) {
            $data->{'externalDocs'} = $this->serializer->serialize($object->getExternalDocs(), 'raw', $context);
        }
        if (null !== $object->getExample()) {
            $data->{'example'} = $object->getExample();
        }

        return $data;
    }
}
