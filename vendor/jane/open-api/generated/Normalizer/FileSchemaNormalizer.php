<?php

namespace Joli\Jane\OpenApi\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class FileSchemaNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Joli\\Jane\\OpenApi\\Model\\FileSchema') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Joli\Jane\OpenApi\Model\FileSchema) {
            return true;
        }

        return false;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \Joli\Jane\OpenApi\Model\FileSchema();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'format')) {
            $object->setFormat($data->{'format'});
        }
        if (property_exists($data, 'title')) {
            $object->setTitle($data->{'title'});
        }
        if (property_exists($data, 'description')) {
            $object->setDescription($data->{'description'});
        }
        if (property_exists($data, 'default')) {
            $object->setDefault($data->{'default'});
        }
        if (property_exists($data, 'required')) {
            $values = [];
            foreach ($data->{'required'} as $value) {
                $values[] = $value;
            }
            $object->setRequired($values);
        }
        if (property_exists($data, 'type')) {
            $object->setType($data->{'type'});
        }
        if (property_exists($data, 'readOnly')) {
            $object->setReadOnly($data->{'readOnly'});
        }
        if (property_exists($data, 'externalDocs')) {
            $object->setExternalDocs($this->serializer->deserialize($data->{'externalDocs'}, 'Joli\\Jane\\OpenApi\\Model\\ExternalDocs', 'raw', $context));
        }
        if (property_exists($data, 'example')) {
            $object->setExample($data->{'example'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getFormat()) {
            $data->{'format'} = $object->getFormat();
        }
        if (null !== $object->getTitle()) {
            $data->{'title'} = $object->getTitle();
        }
        if (null !== $object->getDescription()) {
            $data->{'description'} = $object->getDescription();
        }
        if (null !== $object->getDefault()) {
            $data->{'default'} = $object->getDefault();
        }
        if (null !== $object->getRequired()) {
            $values = [];
            foreach ($object->getRequired() as $value) {
                $values[] = $value;
            }
            $data->{'required'} = $values;
        }
        if (null !== $object->getType()) {
            $data->{'type'} = $object->getType();
        }
        if (null !== $object->getReadOnly()) {
            $data->{'readOnly'} = $object->getReadOnly();
        }
        if (null !== $object->getExternalDocs()) {
            $data->{'externalDocs'} = $this->serializer->serialize($object->getExternalDocs(), 'raw', $context);
        }
        if (null !== $object->getExample()) {
            $data->{'example'} = $object->getExample();
        }

        return $data;
    }
}
