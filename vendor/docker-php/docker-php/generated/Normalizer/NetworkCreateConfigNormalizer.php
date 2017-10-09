<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class NetworkCreateConfigNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\NetworkCreateConfig') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\NetworkCreateConfig) {
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
        $object = new \Docker\API\Model\NetworkCreateConfig();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Name')) {
            $object->setName($data->{'Name'});
        }
        if (property_exists($data, 'CheckDuplicate')) {
            $object->setCheckDuplicate($data->{'CheckDuplicate'});
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
        if (property_exists($data, 'Options')) {
            $values = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'Options'} as $key => $value) {
                $values[$key] = $value;
            }
            $object->setOptions($values);
        }
        if (property_exists($data, 'Labels')) {
            $values_1 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            foreach ($data->{'Labels'} as $key_1 => $value_1) {
                $values_1[$key_1] = $value_1;
            }
            $object->setLabels($values_1);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getName()) {
            $data->{'Name'} = $object->getName();
        }
        if (null !== $object->getCheckDuplicate()) {
            $data->{'CheckDuplicate'} = $object->getCheckDuplicate();
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
        if (null !== $object->getOptions()) {
            $values = new \stdClass();
            foreach ($object->getOptions() as $key => $value) {
                $values->{$key} = $value;
            }
            $data->{'Options'} = $values;
        }
        if (null !== $object->getLabels()) {
            $values_1 = new \stdClass();
            foreach ($object->getLabels() as $key_1 => $value_1) {
                $values_1->{$key_1} = $value_1;
            }
            $data->{'Labels'} = $values_1;
        }

        return $data;
    }
}
