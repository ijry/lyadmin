<?php

namespace Joli\Jane\OpenApi\Tests\Expected\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class SchemaNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\Schema') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Tests\Expected\Model\Schema) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $object = new \Joli\Jane\OpenApi\Tests\Expected\Model\Schema();
        if (property_exists($data, 'stringProperty')) {
            $object->setStringProperty($data->{'stringProperty'});
        }
        if (property_exists($data, 'integerProperty')) {
            $object->setIntegerProperty($data->{'integerProperty'});
        }
        if (property_exists($data, 'floatProperty')) {
            $object->setFloatProperty($data->{'floatProperty'});
        }
        if (property_exists($data, 'arrayProperty')) {
            $values = [];
            foreach ($data->{'arrayProperty'} as $value) {
                $values[] = $value;
            }
            $object->setArrayProperty($values);
        }
        if (property_exists($data, 'mapProperty')) {
            $values_1 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'mapProperty'} as $key => $value_1) {
                $values_1[$key] = $value_1;
            }
            $object->setMapProperty($values_1);
        }
        if (property_exists($data, 'objectProperty')) {
            $object->setObjectProperty($this->serializer->deserialize($data->{'objectProperty'}, 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\ObjectProperty', 'raw', $context));
        }
        if (property_exists($data, 'objectRefProperty')) {
            $object->setObjectRefProperty($this->serializer->deserialize($data->{'objectRefProperty'}, 'Joli\\Jane\\OpenApi\\Tests\\Expected\\Model\\Schema', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getStringProperty()) {
            $data->{'stringProperty'} = $object->getStringProperty();
        }
        if (null !== $object->getIntegerProperty()) {
            $data->{'integerProperty'} = $object->getIntegerProperty();
        }
        if (null !== $object->getFloatProperty()) {
            $data->{'floatProperty'} = $object->getFloatProperty();
        }
        if (null !== $object->getArrayProperty()) {
            $values = [];
            foreach ($object->getArrayProperty() as $value) {
                $values[] = $value;
            }
            $data->{'arrayProperty'} = $values;
        }
        if (null !== $object->getMapProperty()) {
            $values_1 = new \stdClass();
            foreach ($object->getMapProperty() as $key => $value_1) {
                $values_1->{$key} = $value_1;
            }
            $data->{'mapProperty'} = $values_1;
        }
        if (null !== $object->getObjectProperty()) {
            $data->{'objectProperty'} = $this->serializer->serialize($object->getObjectProperty(), 'raw', $context);
        }
        if (null !== $object->getObjectRefProperty()) {
            $data->{'objectRefProperty'} = $this->serializer->serialize($object->getObjectRefProperty(), 'raw', $context);
        }

        return $data;
    }
}
