<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class AuthConfigNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\AuthConfig') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\AuthConfig) {
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
        $object = new \Docker\API\Model\AuthConfig();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'username')) {
            $object->setUsername($data->{'username'});
        }
        if (property_exists($data, 'password')) {
            $object->setPassword($data->{'password'});
        }
        if (property_exists($data, 'email')) {
            $object->setEmail($data->{'email'});
        }
        if (property_exists($data, 'serveraddress')) {
            $object->setServeraddress($data->{'serveraddress'});
        }
        if (property_exists($data, 'registrytoken')) {
            $object->setRegistrytoken($data->{'registrytoken'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getUsername()) {
            $data->{'username'} = $object->getUsername();
        }
        if (null !== $object->getPassword()) {
            $data->{'password'} = $object->getPassword();
        }
        if (null !== $object->getEmail()) {
            $data->{'email'} = $object->getEmail();
        }
        if (null !== $object->getServeraddress()) {
            $data->{'serveraddress'} = $object->getServeraddress();
        }
        if (null !== $object->getRegistrytoken()) {
            $data->{'registrytoken'} = $object->getRegistrytoken();
        }

        return $data;
    }
}
