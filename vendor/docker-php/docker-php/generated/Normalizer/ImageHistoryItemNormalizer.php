<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ImageHistoryItemNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\ImageHistoryItem') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\ImageHistoryItem) {
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
        $object = new \Docker\API\Model\ImageHistoryItem();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Id')) {
            $object->setId($data->{'Id'});
        }
        if (property_exists($data, 'Created')) {
            $object->setCreated($data->{'Created'});
        }
        if (property_exists($data, 'CreatedBy')) {
            $object->setCreatedBy($data->{'CreatedBy'});
        }
        if (property_exists($data, 'Tags')) {
            $value = $data->{'Tags'};
            if (is_array($data->{'Tags'})) {
                $values = [];
                foreach ($data->{'Tags'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'Tags'})) {
                $value = $data->{'Tags'};
            }
            $object->setTags($value);
        }
        if (property_exists($data, 'Size')) {
            $object->setSize($data->{'Size'});
        }
        if (property_exists($data, 'Comment')) {
            $object->setComment($data->{'Comment'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getId()) {
            $data->{'Id'} = $object->getId();
        }
        if (null !== $object->getCreated()) {
            $data->{'Created'} = $object->getCreated();
        }
        if (null !== $object->getCreatedBy()) {
            $data->{'CreatedBy'} = $object->getCreatedBy();
        }
        $value = $object->getTags();
        if (is_array($object->getTags())) {
            $values = [];
            foreach ($object->getTags() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getTags())) {
            $value = $object->getTags();
        }
        $data->{'Tags'} = $value;
        if (null !== $object->getSize()) {
            $data->{'Size'} = $object->getSize();
        }
        if (null !== $object->getComment()) {
            $data->{'Comment'} = $object->getComment();
        }

        return $data;
    }
}
