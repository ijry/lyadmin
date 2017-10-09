<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class FormDataParameterSubSchemaNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\FormDataParameterSubSchema') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\FormDataParameterSubSchema) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\FormDataParameterSubSchema();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'required')) {
            $object->setRequired($data->{'required'});
        }
        if (property_exists($data, 'in')) {
            $object->setIn($data->{'in'});
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'name')) {
            $object->setName($data->{'name'});
        }
        if (property_exists($data, 'allowEmptyValue')) {
            $object->setAllowEmptyValue($data->{'allowEmptyValue'});
        }
        if (property_exists($data, 'type')) {
            $object->setType($data->{'type'});
        }
        if (property_exists($data, 'format')) {
            $object->setFormat($data->{'format'});
        }
        if (property_exists($data, 'items')) {
            $object->setItems($this->serializer->deserialize($data->{'items'}, 'Joli\\Jane\\OpenApi\\Model\\PrimitivesItems', 'raw', $context));
        }
        if (property_exists($data, 'collectionFormat')) {
            $object->setCollectionFormat($data->{'collectionFormat'});
        }
        if (property_exists($data, 'default')) {
            $object->setDefault($data->{'default'});
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
        if (property_exists($data, 'enum')) {
            $values = [];
            foreach ($data->{'enum'} as $value) {
                $values[] = $value;
            }
            $object->setEnum($values);
        }
        if (property_exists($data, 'multipleOf')) {
            $object->setMultipleOf($data->{'multipleOf'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getRequired()) {
            $data->{'required'} = $object->getRequired();
        }
        if (null !== $object->getIn()) {
            $data->{'in'} = $object->getIn();
        }
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getName()) {
            $data->{'name'} = $object->getName();
        }
        if (null !== $object->getAllowEmptyValue()) {
            $data->{'allowEmptyValue'} = $object->getAllowEmptyValue();
        }
        if (null !== $object->getType()) {
            $data->{'type'} = $object->getType();
        }
        if (null !== $object->getFormat()) {
            $data->{'format'} = $object->getFormat();
        }
        if (null !== $object->getItems()) {
            $data->{'items'} = $this->serializer->serialize($object->getItems(), 'raw', $context);
        }
        if (null !== $object->getCollectionFormat()) {
            $data->{'collectionFormat'} = $object->getCollectionFormat();
        }
        if (null !== $object->getDefault()) {
            $data->{'default'} = $object->getDefault();
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
        if (null !== $object->getEnum()) {
            $values = [];
            foreach ($object->getEnum() as $value) {
                $values[] = $value;
            }
            $data->{'enum'} = $values;
        }
        if (null !== $object->getMultipleOf()) {
            $data->{'multipleOf'} = $object->getMultipleOf();
        }

        return $data;
    }
}
