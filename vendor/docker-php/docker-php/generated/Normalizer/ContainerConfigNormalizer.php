<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ContainerConfigNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ContainerConfig') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ContainerConfig) {
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
        $object = new \Docker\API\Model\ContainerConfig();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Hostname')) {
            $object->setHostname($data->{'Hostname'});
        }
        if (property_exists($data, 'Domainname')) {
            $object->setDomainname($data->{'Domainname'});
        }
        if (property_exists($data, 'User')) {
            $object->setUser($data->{'User'});
        }
        if (property_exists($data, 'AttachStdin')) {
            $object->setAttachStdin($data->{'AttachStdin'});
        }
        if (property_exists($data, 'AttachStdout')) {
            $object->setAttachStdout($data->{'AttachStdout'});
        }
        if (property_exists($data, 'AttachStderr')) {
            $object->setAttachStderr($data->{'AttachStderr'});
        }
        if (property_exists($data, 'Tty')) {
            $object->setTty($data->{'Tty'});
        }
        if (property_exists($data, 'OpenStdin')) {
            $object->setOpenStdin($data->{'OpenStdin'});
        }
        if (property_exists($data, 'StdinOnce')) {
            $object->setStdinOnce($data->{'StdinOnce'});
        }
        if (property_exists($data, 'Env')) {
            $value = $data->{'Env'};
            if (is_array($data->{'Env'})) {
                $values = [];
                foreach ($data->{'Env'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'Env'})) {
                $value = $data->{'Env'};
            }
            $object->setEnv($value);
        }
        if (property_exists($data, 'Cmd')) {
            $value_2 = $data->{'Cmd'};
            if (is_array($data->{'Cmd'})) {
                $values_1 = [];
                foreach ($data->{'Cmd'} as $value_3) {
                    $values_1[] = $value_3;
                }
                $value_2 = $values_1;
            }
            if (is_string($data->{'Cmd'})) {
                $value_2 = $data->{'Cmd'};
            }
            $object->setCmd($value_2);
        }
        if (property_exists($data, 'Entrypoint')) {
            $value_4 = $data->{'Entrypoint'};
            if (is_array($data->{'Entrypoint'})) {
                $values_2 = [];
                foreach ($data->{'Entrypoint'} as $value_5) {
                    $values_2[] = $value_5;
                }
                $value_4 = $values_2;
            }
            if (is_string($data->{'Entrypoint'})) {
                $value_4 = $data->{'Entrypoint'};
            }
            $object->setEntrypoint($value_4);
        }
        if (property_exists($data, 'Image')) {
            $object->setImage($data->{'Image'});
        }
        if (property_exists($data, 'Labels')) {
            $value_6 = $data->{'Labels'};
            if (is_object($data->{'Labels'})) {
                $values_3 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($data->{'Labels'} as $key => $value_7) {
                    $values_3[$key] = $value_7;
                }
                $value_6 = $values_3;
            }
            if (is_null($data->{'Labels'})) {
                $value_6 = $data->{'Labels'};
            }
            $object->setLabels($value_6);
        }
        if (property_exists($data, 'Volumes')) {
            $value_8 = $data->{'Volumes'};
            if (is_object($data->{'Volumes'})) {
                $values_4 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($data->{'Volumes'} as $key_1 => $value_9) {
                    $values_4[$key_1] = $value_9;
                }
                $value_8 = $values_4;
            }
            if (is_null($data->{'Volumes'})) {
                $value_8 = $data->{'Volumes'};
            }
            $object->setVolumes($value_8);
        }
        if (property_exists($data, 'WorkingDir')) {
            $object->setWorkingDir($data->{'WorkingDir'});
        }
        if (property_exists($data, 'NetworkDisabled')) {
            $object->setNetworkDisabled($data->{'NetworkDisabled'});
        }
        if (property_exists($data, 'MacAddress')) {
            $object->setMacAddress($data->{'MacAddress'});
        }
        if (property_exists($data, 'ExposedPorts')) {
            $value_10 = $data->{'ExposedPorts'};
            if (is_object($data->{'ExposedPorts'})) {
                $values_5 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($data->{'ExposedPorts'} as $key_2 => $value_11) {
                    $values_5[$key_2] = $value_11;
                }
                $value_10 = $values_5;
            }
            if (is_null($data->{'ExposedPorts'})) {
                $value_10 = $data->{'ExposedPorts'};
            }
            $object->setExposedPorts($value_10);
        }
        if (property_exists($data, 'StopSignal')) {
            $object->setStopSignal($data->{'StopSignal'});
        }
        if (property_exists($data, 'HostConfig')) {
            $object->setHostConfig($this->serializer->deserialize($data->{'HostConfig'}, 'Docker\\API\\Model\\HostConfig', 'raw', $context));
        }
        if (property_exists($data, 'NetworkingConfig')) {
            $object->setNetworkingConfig($this->serializer->deserialize($data->{'NetworkingConfig'}, 'Docker\\API\\Model\\NetworkingConfig', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getHostname()) {
            $data->{'Hostname'} = $object->getHostname();
        }
        if (null !== $object->getDomainname()) {
            $data->{'Domainname'} = $object->getDomainname();
        }
        if (null !== $object->getUser()) {
            $data->{'User'} = $object->getUser();
        }
        if (null !== $object->getAttachStdin()) {
            $data->{'AttachStdin'} = $object->getAttachStdin();
        }
        if (null !== $object->getAttachStdout()) {
            $data->{'AttachStdout'} = $object->getAttachStdout();
        }
        if (null !== $object->getAttachStderr()) {
            $data->{'AttachStderr'} = $object->getAttachStderr();
        }
        if (null !== $object->getTty()) {
            $data->{'Tty'} = $object->getTty();
        }
        if (null !== $object->getOpenStdin()) {
            $data->{'OpenStdin'} = $object->getOpenStdin();
        }
        if (null !== $object->getStdinOnce()) {
            $data->{'StdinOnce'} = $object->getStdinOnce();
        }
        $value = $object->getEnv();
        if (is_array($object->getEnv())) {
            $values = [];
            foreach ($object->getEnv() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getEnv())) {
            $value = $object->getEnv();
        }
        $data->{'Env'} = $value;
        if (null !== $object->getCmd()) {
            $value_2 = $object->getCmd();
            if (is_array($object->getCmd())) {
                $values_1 = [];
                foreach ($object->getCmd() as $value_3) {
                    $values_1[] = $value_3;
                }
                $value_2 = $values_1;
            }
            if (is_string($object->getCmd())) {
                $value_2 = $object->getCmd();
            }
            $data->{'Cmd'} = $value_2;
        }
        if (null !== $object->getEntrypoint()) {
            $value_4 = $object->getEntrypoint();
            if (is_array($object->getEntrypoint())) {
                $values_2 = [];
                foreach ($object->getEntrypoint() as $value_5) {
                    $values_2[] = $value_5;
                }
                $value_4 = $values_2;
            }
            if (is_string($object->getEntrypoint())) {
                $value_4 = $object->getEntrypoint();
            }
            $data->{'Entrypoint'} = $value_4;
        }
        if (null !== $object->getImage()) {
            $data->{'Image'} = $object->getImage();
        }
        $value_6 = $object->getLabels();
        if (is_object($object->getLabels())) {
            $values_3 = new \stdClass();
            foreach ($object->getLabels() as $key => $value_7) {
                $values_3->{$key} = $value_7;
            }
            $value_6 = $values_3;
        }
        if (is_null($object->getLabels())) {
            $value_6 = $object->getLabels();
        }
        $data->{'Labels'} = $value_6;
        $value_8          = $object->getVolumes();
        if (is_object($object->getVolumes())) {
            $values_4 = new \stdClass();
            foreach ($object->getVolumes() as $key_1 => $value_9) {
                $values_4->{$key_1} = $value_9;
            }
            $value_8 = $values_4;
        }
        if (is_null($object->getVolumes())) {
            $value_8 = $object->getVolumes();
        }
        $data->{'Volumes'} = $value_8;
        if (null !== $object->getWorkingDir()) {
            $data->{'WorkingDir'} = $object->getWorkingDir();
        }
        if (null !== $object->getNetworkDisabled()) {
            $data->{'NetworkDisabled'} = $object->getNetworkDisabled();
        }
        if (null !== $object->getMacAddress()) {
            $data->{'MacAddress'} = $object->getMacAddress();
        }
        $value_10 = $object->getExposedPorts();
        if (is_object($object->getExposedPorts())) {
            $values_5 = new \stdClass();
            foreach ($object->getExposedPorts() as $key_2 => $value_11) {
                $values_5->{$key_2} = $value_11;
            }
            $value_10 = $values_5;
        }
        if (is_null($object->getExposedPorts())) {
            $value_10 = $object->getExposedPorts();
        }
        $data->{'ExposedPorts'} = $value_10;
        if (null !== $object->getStopSignal()) {
            $data->{'StopSignal'} = $object->getStopSignal();
        }
        if (null !== $object->getHostConfig()) {
            $data->{'HostConfig'} = $this->serializer->serialize($object->getHostConfig(), 'raw', $context);
        }
        if (null !== $object->getNetworkingConfig()) {
            $data->{'NetworkingConfig'} = $this->serializer->serialize($object->getNetworkingConfig(), 'raw', $context);
        }

        return $data;
    }
}
