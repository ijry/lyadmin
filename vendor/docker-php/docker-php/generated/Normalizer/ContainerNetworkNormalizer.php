<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ContainerNetworkNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ContainerNetwork') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ContainerNetwork) {
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
        $object = new \Docker\API\Model\ContainerNetwork();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'NetworkID')) {
            $object->setNetworkID($data->{'NetworkID'});
        }
        if (property_exists($data, 'EndpointID')) {
            $object->setEndpointID($data->{'EndpointID'});
        }
        if (property_exists($data, 'Gateway')) {
            $object->setGateway($data->{'Gateway'});
        }
        if (property_exists($data, 'IPAddress')) {
            $object->setIPAddress($data->{'IPAddress'});
        }
        if (property_exists($data, 'IPPrefixLen')) {
            $object->setIPPrefixLen($data->{'IPPrefixLen'});
        }
        if (property_exists($data, 'IPv6Gateway')) {
            $object->setIPv6Gateway($data->{'IPv6Gateway'});
        }
        if (property_exists($data, 'GlobalIPv6Address')) {
            $object->setGlobalIPv6Address($data->{'GlobalIPv6Address'});
        }
        if (property_exists($data, 'GlobalIPv6PrefixLen')) {
            $object->setGlobalIPv6PrefixLen($data->{'GlobalIPv6PrefixLen'});
        }
        if (property_exists($data, 'MacAddress')) {
            $object->setMacAddress($data->{'MacAddress'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getNetworkID()) {
            $data->{'NetworkID'} = $object->getNetworkID();
        }
        if (null !== $object->getEndpointID()) {
            $data->{'EndpointID'} = $object->getEndpointID();
        }
        if (null !== $object->getGateway()) {
            $data->{'Gateway'} = $object->getGateway();
        }
        if (null !== $object->getIPAddress()) {
            $data->{'IPAddress'} = $object->getIPAddress();
        }
        if (null !== $object->getIPPrefixLen()) {
            $data->{'IPPrefixLen'} = $object->getIPPrefixLen();
        }
        if (null !== $object->getIPv6Gateway()) {
            $data->{'IPv6Gateway'} = $object->getIPv6Gateway();
        }
        if (null !== $object->getGlobalIPv6Address()) {
            $data->{'GlobalIPv6Address'} = $object->getGlobalIPv6Address();
        }
        if (null !== $object->getGlobalIPv6PrefixLen()) {
            $data->{'GlobalIPv6PrefixLen'} = $object->getGlobalIPv6PrefixLen();
        }
        if (null !== $object->getMacAddress()) {
            $data->{'MacAddress'} = $object->getMacAddress();
        }

        return $data;
    }
}
