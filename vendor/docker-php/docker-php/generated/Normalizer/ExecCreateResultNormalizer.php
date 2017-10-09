<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ExecCreateResultNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ExecCreateResult') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ExecCreateResult) {
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
        $object = new \Docker\API\Model\ExecCreateResult();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Id')) {
            $object->setId($data->{'Id'});
        }
        if (property_exists($data, 'Warnings')) {
            $value = $data->{'Warnings'};
            if (is_array($data->{'Warnings'})) {
                $values = [];
                foreach ($data->{'Warnings'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'Warnings'})) {
                $value = $data->{'Warnings'};
            }
            $object->setWarnings($value);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getId()) {
            $data->{'Id'} = $object->getId();
        }
        $value = $object->getWarnings();
        if (is_array($object->getWarnings())) {
            $values = [];
            foreach ($object->getWarnings() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getWarnings())) {
            $value = $object->getWarnings();
        }
        $data->{'Warnings'} = $value;

        return $data;
    }
}
