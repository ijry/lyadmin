<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ContainerInfoNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ContainerInfo') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ContainerInfo) {
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
        $object = new \Docker\API\Model\ContainerInfo();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Id')) {
            $object->setId($data->{'Id'});
        }
        if (property_exists($data, 'Names')) {
            $value = $data->{'Names'};
            if (is_array($data->{'Names'})) {
                $values = [];
                foreach ($data->{'Names'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'Names'})) {
                $value = $data->{'Names'};
            }
            $object->setNames($value);
        }
        if (property_exists($data, 'Image')) {
            $object->setImage($data->{'Image'});
        }
        if (property_exists($data, 'ImageID')) {
            $object->setImageID($data->{'ImageID'});
        }
        if (property_exists($data, 'Command')) {
            $object->setCommand($data->{'Command'});
        }
        if (property_exists($data, 'Created')) {
            $object->setCreated($data->{'Created'});
        }
        if (property_exists($data, 'State')) {
            $object->setState($data->{'State'});
        }
        if (property_exists($data, 'Status')) {
            $object->setStatus($data->{'Status'});
        }
        if (property_exists($data, 'Ports')) {
            $value_2 = $data->{'Ports'};
            if (is_array($data->{'Ports'})) {
                $values_1 = [];
                foreach ($data->{'Ports'} as $value_3) {
                    $values_1[] = $this->serializer->deserialize($value_3, 'Docker\\API\\Model\\Port', 'raw', $context);
                }
                $value_2 = $values_1;
            }
            if (is_null($data->{'Ports'})) {
                $value_2 = $data->{'Ports'};
            }
            $object->setPorts($value_2);
        }
        if (property_exists($data, 'Labels')) {
            $value_4 = $data->{'Labels'};
            if (is_object($data->{'Labels'})) {
                $values_2 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($data->{'Labels'} as $key => $value_5) {
                    $values_2[$key] = $value_5;
                }
                $value_4 = $values_2;
            }
            if (is_null($data->{'Labels'})) {
                $value_4 = $data->{'Labels'};
            }
            $object->setLabels($value_4);
        }
        if (property_exists($data, 'SizeRw')) {
            $object->setSizeRw($data->{'SizeRw'});
        }
        if (property_exists($data, 'SizeRootFs')) {
            $object->setSizeRootFs($data->{'SizeRootFs'});
        }
        if (property_exists($data, 'HostConfig')) {
            $object->setHostConfig($this->serializer->deserialize($data->{'HostConfig'}, 'Docker\\API\\Model\\HostConfig', 'raw', $context));
        }
        if (property_exists($data, 'NetworkSettings')) {
            $object->setNetworkSettings($this->serializer->deserialize($data->{'NetworkSettings'}, 'Docker\\API\\Model\\NetworkConfig', 'raw', $context));
        }
        if (property_exists($data, 'Mounts')) {
            $value_6 = $data->{'Mounts'};
            if (is_array($data->{'Mounts'})) {
                $values_3 = [];
                foreach ($data->{'Mounts'} as $value_7) {
                    $values_3[] = $this->serializer->deserialize($value_7, 'Docker\\API\\Model\\Mount', 'raw', $context);
                }
                $value_6 = $values_3;
            }
            if (is_null($data->{'Mounts'})) {
                $value_6 = $data->{'Mounts'};
            }
            $object->setMounts($value_6);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getId()) {
            $data->{'Id'} = $object->getId();
        }
        $value = $object->getNames();
        if (is_array($object->getNames())) {
            $values = [];
            foreach ($object->getNames() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getNames())) {
            $value = $object->getNames();
        }
        $data->{'Names'} = $value;
        if (null !== $object->getImage()) {
            $data->{'Image'} = $object->getImage();
        }
        if (null !== $object->getImageID()) {
            $data->{'ImageID'} = $object->getImageID();
        }
        if (null !== $object->getCommand()) {
            $data->{'Command'} = $object->getCommand();
        }
        if (null !== $object->getCreated()) {
            $data->{'Created'} = $object->getCreated();
        }
        if (null !== $object->getState()) {
            $data->{'State'} = $object->getState();
        }
        if (null !== $object->getStatus()) {
            $data->{'Status'} = $object->getStatus();
        }
        $value_2 = $object->getPorts();
        if (is_array($object->getPorts())) {
            $values_1 = [];
            foreach ($object->getPorts() as $value_3) {
                $values_1[] = $this->serializer->serialize($value_3, 'raw', $context);
            }
            $value_2 = $values_1;
        }
        if (is_null($object->getPorts())) {
            $value_2 = $object->getPorts();
        }
        $data->{'Ports'} = $value_2;
        $value_4         = $object->getLabels();
        if (is_object($object->getLabels())) {
            $values_2 = new \stdClass();
            foreach ($object->getLabels() as $key => $value_5) {
                $values_2->{$key} = $value_5;
            }
            $value_4 = $values_2;
        }
        if (is_null($object->getLabels())) {
            $value_4 = $object->getLabels();
        }
        $data->{'Labels'} = $value_4;
        if (null !== $object->getSizeRw()) {
            $data->{'SizeRw'} = $object->getSizeRw();
        }
        if (null !== $object->getSizeRootFs()) {
            $data->{'SizeRootFs'} = $object->getSizeRootFs();
        }
        if (null !== $object->getHostConfig()) {
            $data->{'HostConfig'} = $this->serializer->serialize($object->getHostConfig(), 'raw', $context);
        }
        if (null !== $object->getNetworkSettings()) {
            $data->{'NetworkSettings'} = $this->serializer->serialize($object->getNetworkSettings(), 'raw', $context);
        }
        $value_6 = $object->getMounts();
        if (is_array($object->getMounts())) {
            $values_3 = [];
            foreach ($object->getMounts() as $value_7) {
                $values_3[] = $this->serializer->serialize($value_7, 'raw', $context);
            }
            $value_6 = $values_3;
        }
        if (is_null($object->getMounts())) {
            $value_6 = $object->getMounts();
        }
        $data->{'Mounts'} = $value_6;

        return $data;
    }
}
