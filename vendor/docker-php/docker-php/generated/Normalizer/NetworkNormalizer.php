<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class NetworkNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\Network') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\Network) {
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
        $object = new \Docker\API\Model\Network();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Name')) {
            $object->setName($data->{'Name'});
        }
        if (property_exists($data, 'Id')) {
            $object->setId($data->{'Id'});
        }
        if (property_exists($data, 'Scope')) {
            $object->setScope($data->{'Scope'});
        }
        if (property_exists($data, 'Driver')) {
            $object->setDriver($data->{'Driver'});
        }
        if (property_exists($data, 'EnableIPv6')) {
            $object->setEnableIPv6($data->{'EnableIPv6'});
        }
        if (property_exists($data, 'IPAM')) {
            $object->setIPAM($this->serializer->deserialize($data->{'IPAM'}, 'Docker\\API\\Model\\IPAM', 'raw', $context));
        }
        if (property_exists($data, 'Internal')) {
            $object->setInternal($data->{'Internal'});
        }
        if (property_exists($data, 'Containers')) {
            $values = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'Containers'} as $key => $value) {
                $values[$key] = $this->serializer->deserialize($value, 'Docker\\API\\Model\\NetworkContainer', 'raw', $context);
            }
            $object->setContainers($values);
        }
        if (property_exists($data, 'Options')) {
            $values_1 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'Options'} as $key_1 => $value_1) {
                $values_1[$key_1] = $value_1;
            }
            $object->setOptions($values_1);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getName()) {
            $data->{'Name'} = $object->getName();
        }
        if (null !== $object->getId()) {
            $data->{'Id'} = $object->getId();
        }
        if (null !== $object->getScope()) {
            $data->{'Scope'} = $object->getScope();
        }
        if (null !== $object->getDriver()) {
            $data->{'Driver'} = $object->getDriver();
        }
        if (null !== $object->getEnableIPv6()) {
            $data->{'EnableIPv6'} = $object->getEnableIPv6();
        }
        if (null !== $object->getIPAM()) {
            $data->{'IPAM'} = $this->serializer->serialize($object->getIPAM(), 'raw', $context);
        }
        if (null !== $object->getInternal()) {
            $data->{'Internal'} = $object->getInternal();
        }
        if (null !== $object->getContainers()) {
            $values = new \stdClass();
            foreach ($object->getContainers() as $key => $value) {
                $values->{$key} = $this->serializer->serialize($value, 'raw', $context);
            }
            $data->{'Containers'} = $values;
        }
        if (null !== $object->getOptions()) {
            $values_1 = new \stdClass();
            foreach ($object->getOptions() as $key_1 => $value_1) {
                $values_1->{$key_1} = $value_1;
            }
            $data->{'Options'} = $values_1;
        }

        return $data;
    }
}
