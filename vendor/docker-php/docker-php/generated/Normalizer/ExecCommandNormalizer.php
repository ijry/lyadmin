<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ExecCommandNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ExecCommand') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ExecCommand) {
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
        $object = new \Docker\API\Model\ExecCommand();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'ID')) {
            $object->setID($data->{'ID'});
        }
        if (property_exists($data, 'Running')) {
            $object->setRunning($data->{'Running'});
        }
        if (property_exists($data, 'ExitCode')) {
            $object->setExitCode($data->{'ExitCode'});
        }
        if (property_exists($data, 'ProcessConfig')) {
            $object->setProcessConfig($this->serializer->deserialize($data->{'ProcessConfig'}, 'Docker\\API\\Model\\ProcessConfig', 'raw', $context));
        }
        if (property_exists($data, 'OpenStdin')) {
            $object->setOpenStdin($data->{'OpenStdin'});
        }
        if (property_exists($data, 'OpenStderr')) {
            $object->setOpenStderr($data->{'OpenStderr'});
        }
        if (property_exists($data, 'OpenStdout')) {
            $object->setOpenStdout($data->{'OpenStdout'});
        }
        if (property_exists($data, 'Container')) {
            $object->setContainer($this->serializer->deserialize($data->{'Container'}, 'Docker\\API\\Model\\Container', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getID()) {
            $data->{'ID'} = $object->getID();
        }
        if (null !== $object->getRunning()) {
            $data->{'Running'} = $object->getRunning();
        }
        if (null !== $object->getExitCode()) {
            $data->{'ExitCode'} = $object->getExitCode();
        }
        if (null !== $object->getProcessConfig()) {
            $data->{'ProcessConfig'} = $this->serializer->serialize($object->getProcessConfig(), 'raw', $context);
        }
        if (null !== $object->getOpenStdin()) {
            $data->{'OpenStdin'} = $object->getOpenStdin();
        }
        if (null !== $object->getOpenStderr()) {
            $data->{'OpenStderr'} = $object->getOpenStderr();
        }
        if (null !== $object->getOpenStdout()) {
            $data->{'OpenStdout'} = $object->getOpenStdout();
        }
        if (null !== $object->getContainer()) {
            $data->{'Container'} = $this->serializer->serialize($object->getContainer(), 'raw', $context);
        }

        return $data;
    }
}
