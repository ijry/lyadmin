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
        if (property_exists($data, 'date')) {
            $object->setDate(\DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->{'date'}));
        }
        if (property_exists($data, 'dateOrNull')) {
            $value = $data->{'dateOrNull'};
            if (is_string($data->{'dateOrNull'}) and false !== \DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->{'dateOrNull'})) {
                $value = \DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->{'dateOrNull'});
            }
            if (is_null($data->{'dateOrNull'})) {
                $value = $data->{'dateOrNull'};
            }
            $object->setDateOrNull($value);
        }
        if (property_exists($data, 'dateOrNullOrInt')) {
            $value_1 = $data->{'dateOrNullOrInt'};
            if (is_string($data->{'dateOrNullOrInt'}) and false !== \DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->{'dateOrNullOrInt'})) {
                $value_1 = \DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->{'dateOrNullOrInt'});
            }
            if (is_null($data->{'dateOrNullOrInt'})) {
                $value_1 = $data->{'dateOrNullOrInt'};
            }
            if (is_int($data->{'dateOrNullOrInt'})) {
                $value_1 = $data->{'dateOrNullOrInt'};
            }
            $object->setDateOrNullOrInt($value_1);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getDate()) {
            $data->{'date'} = $object->getDate()->format("Y-m-d\TH:i:sP");
        }
        $value = $object->getDateOrNull();
        if (is_object($object->getDateOrNull())) {
            $value = $object->getDateOrNull()->format("Y-m-d\TH:i:sP");
        }
        if (is_null($object->getDateOrNull())) {
            $value = $object->getDateOrNull();
        }
        $data->{'dateOrNull'} = $value;
        $value_1              = $object->getDateOrNullOrInt();
        if (is_object($object->getDateOrNullOrInt())) {
            $value_1 = $object->getDateOrNullOrInt()->format("Y-m-d\TH:i:sP");
        }
        if (is_null($object->getDateOrNullOrInt())) {
            $value_1 = $object->getDateOrNullOrInt();
        }
        if (is_int($object->getDateOrNullOrInt())) {
            $value_1 = $object->getDateOrNullOrInt();
        }
        $data->{'dateOrNullOrInt'} = $value_1;

        return $data;
    }
}
