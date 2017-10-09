<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ContainerStateNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ContainerState') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ContainerState) {
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
        $object = new \Docker\API\Model\ContainerState();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Error')) {
            $object->setError($data->{'Error'});
        }
        if (property_exists($data, 'ExitCode')) {
            $object->setExitCode($data->{'ExitCode'});
        }
        if (property_exists($data, 'FinishedAt')) {
            $object->setFinishedAt($data->{'FinishedAt'});
        }
        if (property_exists($data, 'OOMKilled')) {
            $object->setOOMKilled($data->{'OOMKilled'});
        }
        if (property_exists($data, 'Paused')) {
            $object->setPaused($data->{'Paused'});
        }
        if (property_exists($data, 'Pid')) {
            $object->setPid($data->{'Pid'});
        }
        if (property_exists($data, 'Restarting')) {
            $object->setRestarting($data->{'Restarting'});
        }
        if (property_exists($data, 'Running')) {
            $object->setRunning($data->{'Running'});
        }
        if (property_exists($data, 'StartedAt')) {
            $object->setStartedAt($data->{'StartedAt'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getError()) {
            $data->{'Error'} = $object->getError();
        }
        if (null !== $object->getExitCode()) {
            $data->{'ExitCode'} = $object->getExitCode();
        }
        if (null !== $object->getFinishedAt()) {
            $data->{'FinishedAt'} = $object->getFinishedAt();
        }
        if (null !== $object->getOOMKilled()) {
            $data->{'OOMKilled'} = $object->getOOMKilled();
        }
        if (null !== $object->getPaused()) {
            $data->{'Paused'} = $object->getPaused();
        }
        if (null !== $object->getPid()) {
            $data->{'Pid'} = $object->getPid();
        }
        if (null !== $object->getRestarting()) {
            $data->{'Restarting'} = $object->getRestarting();
        }
        if (null !== $object->getRunning()) {
            $data->{'Running'} = $object->getRunning();
        }
        if (null !== $object->getStartedAt()) {
            $data->{'StartedAt'} = $object->getStartedAt();
        }

        return $data;
    }
}
