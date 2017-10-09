<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class InfoNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\Info') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\Info) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\Info();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'title')) {
            $object->setTitle($data->{'title'});
        }
        if (property_exists($data, 'version')) {
            $object->setVersion($data->{'version'});
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'termsOfService')) {
            $object->setTermsOfService($data->{'termsOfService'});
        }
        if (property_exists($data, 'contact')) {
            $object->setContact($this->serializer->deserialize($data->{'contact'}, 'Joli\\Jane\\OpenApi\\Model\\Contact', 'raw', $context));
        }
        if (property_exists($data, 'license')) {
            $object->setLicense($this->serializer->deserialize($data->{'license'}, 'Joli\\Jane\\OpenApi\\Model\\License', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getTitle()) {
            $data->{'title'} = $object->getTitle();
        }
        if (null !== $object->getVersion()) {
            $data->{'version'} = $object->getVersion();
        }
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getTermsOfService()) {
            $data->{'termsOfService'} = $object->getTermsOfService();
        }
        if (null !== $object->getContact()) {
            $data->{'contact'} = $this->serializer->serialize($object->getContact(), 'raw', $context);
        }
        if (null !== $object->getLicense()) {
            $data->{'license'} = $this->serializer->serialize($object->getLicense(), 'raw', $context);
        }

        return $data;
    }
}
