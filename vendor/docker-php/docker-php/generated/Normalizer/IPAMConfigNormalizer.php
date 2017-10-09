<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class IPAMConfigNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\IPAMConfig') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\IPAMConfig) {
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
        $object = new \Docker\API\Model\IPAMConfig();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Subnet')) {
            $object->setSubnet($data->{'Subnet'});
        }
        if (property_exists($data, 'IPRange')) {
            $object->setIPRange($data->{'IPRange'});
        }
        if (property_exists($data, 'Gateway')) {
            $object->setGateway($data->{'Gateway'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getSubnet()) {
            $data->{'Subnet'} = $object->getSubnet();
        }
        if (null !== $object->getIPRange()) {
            $data->{'IPRange'} = $object->getIPRange();
        }
        if (null !== $object->getGateway()) {
            $data->{'Gateway'} = $object->getGateway();
        }

        return $data;
    }
}
