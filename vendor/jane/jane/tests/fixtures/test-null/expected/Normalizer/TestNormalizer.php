<?php

namespace Joli\Jane\Tests\Expected\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class TestNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\Tests\\Expected\\Model\\Test') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\Tests\Expected\Model\Test) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\Tests\Expected\Model\Test();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'onlyNull')) {
            $object->setOnlyNull($data->{'onlyNull'});
        }
        if (property_exists($data, 'nullOrString')) {
            $value = $data->{'nullOrString'};
            if (is_string($data->{'nullOrString'})) {
                $value = $data->{'nullOrString'};
            }
            if (is_null($data->{'nullOrString'})) {
                $value = $data->{'nullOrString'};
            }
            $object->setNullOrString($value);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data               = new \stdClass();
        $data->{'onlyNull'} = $object->getOnlyNull();
        $value              = $object->getNullOrString();
        if (is_string($object->getNullOrString())) {
            $value = $object->getNullOrString();
        }
        if (is_null($object->getNullOrString())) {
            $value = $object->getNullOrString();
        }
        $data->{'nullOrString'} = $value;

        return $data;
    }
}
