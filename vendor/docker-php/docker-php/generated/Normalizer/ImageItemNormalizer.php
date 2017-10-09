<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ImageItemNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ImageItem') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ImageItem) {
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
        $object = new \Docker\API\Model\ImageItem();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'RepoTags')) {
            $value = $data->{'RepoTags'};
            if (is_array($data->{'RepoTags'})) {
                $values = [];
                foreach ($data->{'RepoTags'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'RepoTags'})) {
                $value = $data->{'RepoTags'};
            }
            $object->setRepoTags($value);
        }
        if (property_exists($data, 'Id')) {
            $object->setId($data->{'Id'});
        }
        if (property_exists($data, 'ParentId')) {
            $object->setParentId($data->{'ParentId'});
        }
        if (property_exists($data, 'Created')) {
            $object->setCreated($data->{'Created'});
        }
        if (property_exists($data, 'Size')) {
            $object->setSize($data->{'Size'});
        }
        if (property_exists($data, 'VirtualSize')) {
            $object->setVirtualSize($data->{'VirtualSize'});
        }
        if (property_exists($data, 'Labels')) {
            $value_2 = $data->{'Labels'};
            if (is_object($data->{'Labels'})) {
                $values_1 = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
                foreach ($data->{'Labels'} as $key => $value_3) {
                    $values_1[$key] = $value_3;
                }
                $value_2 = $values_1;
            }
            if (is_null($data->{'Labels'})) {
                $value_2 = $data->{'Labels'};
            }
            $object->setLabels($value_2);
        }
        if (property_exists($data, 'RepoDigests')) {
            $value_4 = $data->{'RepoDigests'};
            if (is_array($data->{'RepoDigests'})) {
                $values_2 = [];
                foreach ($data->{'RepoDigests'} as $value_5) {
                    $values_2[] = $value_5;
                }
                $value_4 = $values_2;
            }
            if (is_null($data->{'RepoDigests'})) {
                $value_4 = $data->{'RepoDigests'};
            }
            $object->setRepoDigests($value_4);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data  = new \stdClass();
        $value = $object->getRepoTags();
        if (is_array($object->getRepoTags())) {
            $values = [];
            foreach ($object->getRepoTags() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getRepoTags())) {
            $value = $object->getRepoTags();
        }
        $data->{'RepoTags'} = $value;
        if (null !== $object->getId()) {
            $data->{'Id'} = $object->getId();
        }
        if (null !== $object->getParentId()) {
            $data->{'ParentId'} = $object->getParentId();
        }
        if (null !== $object->getCreated()) {
            $data->{'Created'} = $object->getCreated();
        }
        if (null !== $object->getSize()) {
            $data->{'Size'} = $object->getSize();
        }
        if (null !== $object->getVirtualSize()) {
            $data->{'VirtualSize'} = $object->getVirtualSize();
        }
        $value_2 = $object->getLabels();
        if (is_object($object->getLabels())) {
            $values_1 = new \stdClass();
            foreach ($object->getLabels() as $key => $value_3) {
                $values_1->{$key} = $value_3;
            }
            $value_2 = $values_1;
        }
        if (is_null($object->getLabels())) {
            $value_2 = $object->getLabels();
        }
        $data->{'Labels'} = $value_2;
        $value_4          = $object->getRepoDigests();
        if (is_array($object->getRepoDigests())) {
            $values_2 = [];
            foreach ($object->getRepoDigests() as $value_5) {
                $values_2[] = $value_5;
            }
            $value_4 = $values_2;
        }
        if (is_null($object->getRepoDigests())) {
            $value_4 = $object->getRepoDigests();
        }
        $data->{'RepoDigests'} = $value_4;

        return $data;
    }
}
