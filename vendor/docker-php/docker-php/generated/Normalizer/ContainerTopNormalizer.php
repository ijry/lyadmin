<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ContainerTopNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ContainerTop') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ContainerTop) {
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
        $object = new \Docker\API\Model\ContainerTop();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Titles')) {
            $value = $data->{'Titles'};
            if (is_array($data->{'Titles'})) {
                $values = [];
                foreach ($data->{'Titles'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'Titles'})) {
                $value = $data->{'Titles'};
            }
            $object->setTitles($value);
        }
        if (property_exists($data, 'Processes')) {
            $value_2 = $data->{'Processes'};
            if (is_array($data->{'Processes'})) {
                $values_1 = [];
                foreach ($data->{'Processes'} as $value_3) {
                    $value_4 = $value_3;
                    if (is_array($value_3)) {
                        $values_2 = [];
                        foreach ($value_3 as $value_5) {
                            $values_2[] = $value_5;
                        }
                        $value_4 = $values_2;
                    }
                    if (is_null($value_3)) {
                        $value_4 = $value_3;
                    }
                    $values_1[] = $value_4;
                }
                $value_2 = $values_1;
            }
            if (is_null($data->{'Processes'})) {
                $value_2 = $data->{'Processes'};
            }
            $object->setProcesses($value_2);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data  = new \stdClass();
        $value = $object->getTitles();
        if (is_array($object->getTitles())) {
            $values = [];
            foreach ($object->getTitles() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getTitles())) {
            $value = $object->getTitles();
        }
        $data->{'Titles'} = $value;
        $value_2          = $object->getProcesses();
        if (is_array($object->getProcesses())) {
            $values_1 = [];
            foreach ($object->getProcesses() as $value_3) {
                $value_4 = $value_3;
                if (is_array($value_3)) {
                    $values_2 = [];
                    foreach ($value_3 as $value_5) {
                        $values_2[] = $value_5;
                    }
                    $value_4 = $values_2;
                }
                if (is_null($value_3)) {
                    $value_4 = $value_3;
                }
                $values_1[] = $value_4;
            }
            $value_2 = $values_1;
        }
        if (is_null($object->getProcesses())) {
            $value_2 = $object->getProcesses();
        }
        $data->{'Processes'} = $value_2;

        return $data;
    }
}
