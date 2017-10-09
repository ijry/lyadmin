<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ImageNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\Image') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\Image) {
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
        $object = new \Docker\API\Model\Image();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Id')) {
            $object->setId($data->{'Id'});
        }
        if (property_exists($data, 'Container')) {
            $object->setContainer($data->{'Container'});
        }
        if (property_exists($data, 'Comment')) {
            $object->setComment($data->{'Comment'});
        }
        if (property_exists($data, 'Os')) {
            $object->setOs($data->{'Os'});
        }
        if (property_exists($data, 'Architecture')) {
            $object->setArchitecture($data->{'Architecture'});
        }
        if (property_exists($data, 'Parent')) {
            $object->setParent($data->{'Parent'});
        }
        if (property_exists($data, 'ContainerConfig')) {
            $object->setContainerConfig($this->serializer->deserialize($data->{'ContainerConfig'}, 'Docker\\API\\Model\\ContainerConfig', 'raw', $context));
        }
        if (property_exists($data, 'DockerVersion')) {
            $object->setDockerVersion($data->{'DockerVersion'});
        }
        if (property_exists($data, 'VirtualSize')) {
            $object->setVirtualSize($data->{'VirtualSize'});
        }
        if (property_exists($data, 'Size')) {
            $object->setSize($data->{'Size'});
        }
        if (property_exists($data, 'Author')) {
            $object->setAuthor($data->{'Author'});
        }
        if (property_exists($data, 'Created')) {
            $object->setCreated($data->{'Created'});
        }
        if (property_exists($data, 'GraphDriver')) {
            $object->setGraphDriver($this->serializer->deserialize($data->{'GraphDriver'}, 'Docker\\API\\Model\\GraphDriver', 'raw', $context));
        }
        if (property_exists($data, 'RepoDigests')) {
            $value = $data->{'RepoDigests'};
            if (is_array($data->{'RepoDigests'})) {
                $values = [];
                foreach ($data->{'RepoDigests'} as $value_1) {
                    $values[] = $value_1;
                }
                $value = $values;
            }
            if (is_null($data->{'RepoDigests'})) {
                $value = $data->{'RepoDigests'};
            }
            $object->setRepoDigests($value);
        }
        if (property_exists($data, 'RepoTags')) {
            $value_2 = $data->{'RepoTags'};
            if (is_array($data->{'RepoTags'})) {
                $values_1 = [];
                foreach ($data->{'RepoTags'} as $value_3) {
                    $values_1[] = $value_3;
                }
                $value_2 = $values_1;
            }
            if (is_null($data->{'RepoTags'})) {
                $value_2 = $data->{'RepoTags'};
            }
            $object->setRepoTags($value_2);
        }
        if (property_exists($data, 'Config')) {
            $object->setConfig($this->serializer->deserialize($data->{'Config'}, 'Docker\\API\\Model\\ContainerConfig', 'raw', $context));
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getId()) {
            $data->{'Id'} = $object->getId();
        }
        if (null !== $object->getContainer()) {
            $data->{'Container'} = $object->getContainer();
        }
        if (null !== $object->getComment()) {
            $data->{'Comment'} = $object->getComment();
        }
        if (null !== $object->getOs()) {
            $data->{'Os'} = $object->getOs();
        }
        if (null !== $object->getArchitecture()) {
            $data->{'Architecture'} = $object->getArchitecture();
        }
        if (null !== $object->getParent()) {
            $data->{'Parent'} = $object->getParent();
        }
        if (null !== $object->getContainerConfig()) {
            $data->{'ContainerConfig'} = $this->serializer->serialize($object->getContainerConfig(), 'raw', $context);
        }
        if (null !== $object->getDockerVersion()) {
            $data->{'DockerVersion'} = $object->getDockerVersion();
        }
        if (null !== $object->getVirtualSize()) {
            $data->{'VirtualSize'} = $object->getVirtualSize();
        }
        if (null !== $object->getSize()) {
            $data->{'Size'} = $object->getSize();
        }
        if (null !== $object->getAuthor()) {
            $data->{'Author'} = $object->getAuthor();
        }
        if (null !== $object->getCreated()) {
            $data->{'Created'} = $object->getCreated();
        }
        if (null !== $object->getGraphDriver()) {
            $data->{'GraphDriver'} = $this->serializer->serialize($object->getGraphDriver(), 'raw', $context);
        }
        $value = $object->getRepoDigests();
        if (is_array($object->getRepoDigests())) {
            $values = [];
            foreach ($object->getRepoDigests() as $value_1) {
                $values[] = $value_1;
            }
            $value = $values;
        }
        if (is_null($object->getRepoDigests())) {
            $value = $object->getRepoDigests();
        }
        $data->{'RepoDigests'} = $value;
        $value_2               = $object->getRepoTags();
        if (is_array($object->getRepoTags())) {
            $values_1 = [];
            foreach ($object->getRepoTags() as $value_3) {
                $values_1[] = $value_3;
            }
            $value_2 = $values_1;
        }
        if (is_null($object->getRepoTags())) {
            $value_2 = $object->getRepoTags();
        }
        $data->{'RepoTags'} = $value_2;
        if (null !== $object->getConfig()) {
            $data->{'Config'} = $this->serializer->serialize($object->getConfig(), 'raw', $context);
        }

        return $data;
    }
}
