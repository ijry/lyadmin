<?php

namespace Docker\API\Normalizer;

use Joli\Jane\Reference\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class SystemInformationNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'Docker\\API\\Model\\SystemInformation') {
            return false;
        }

        return true;
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \Docker\API\Model\SystemInformation) {
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
        $object = new \Docker\API\Model\SystemInformation();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'Architecture')) {
            $object->setArchitecture($data->{'Architecture'});
        }
        if (property_exists($data, 'ClusterStore')) {
            $object->setClusterStore($data->{'ClusterStore'});
        }
        if (property_exists($data, 'CgroupDriver')) {
            $object->setCgroupDriver($data->{'CgroupDriver'});
        }
        if (property_exists($data, 'Containers')) {
            $object->setContainers($data->{'Containers'});
        }
        if (property_exists($data, 'ContainersRunning')) {
            $object->setContainersRunning($data->{'ContainersRunning'});
        }
        if (property_exists($data, 'ContainersStopped')) {
            $object->setContainersStopped($data->{'ContainersStopped'});
        }
        if (property_exists($data, 'ContainersPaused')) {
            $object->setContainersPaused($data->{'ContainersPaused'});
        }
        if (property_exists($data, 'CpuCfsPeriod')) {
            $object->setCpuCfsPeriod($data->{'CpuCfsPeriod'});
        }
        if (property_exists($data, 'CpuCfsQuota')) {
            $object->setCpuCfsQuota($data->{'CpuCfsQuota'});
        }
        if (property_exists($data, 'Debug')) {
            $object->setDebug($data->{'Debug'});
        }
        if (property_exists($data, 'DiscoveryBackend')) {
            $object->setDiscoveryBackend($data->{'DiscoveryBackend'});
        }
        if (property_exists($data, 'DockerRootDir')) {
            $object->setDockerRootDir($data->{'DockerRootDir'});
        }
        if (property_exists($data, 'Driver')) {
            $object->setDriver($data->{'Driver'});
        }
        if (property_exists($data, 'DriverStatus')) {
            $value = $data->{'DriverStatus'};
            if (is_array($data->{'DriverStatus'})) {
                $values = [];
                foreach ($data->{'DriverStatus'} as $value_1) {
                    $value_2 = $value_1;
                    if (is_array($value_1)) {
                        $values_1 = [];
                        foreach ($value_1 as $value_3) {
                            $values_1[] = $value_3;
                        }
                        $value_2 = $values_1;
                    }
                    if (is_null($value_1)) {
                        $value_2 = $value_1;
                    }
                    $values[] = $value_2;
                }
                $value = $values;
            }
            if (is_null($data->{'DriverStatus'})) {
                $value = $data->{'DriverStatus'};
            }
            $object->setDriverStatus($value);
        }
        if (property_exists($data, 'SystemStatus')) {
            $value_4 = $data->{'SystemStatus'};
            if (is_array($data->{'SystemStatus'})) {
                $values_2 = [];
                foreach ($data->{'SystemStatus'} as $value_5) {
                    $value_6 = $value_5;
                    if (is_array($value_5)) {
                        $values_3 = [];
                        foreach ($value_5 as $value_7) {
                            $values_3[] = $value_7;
                        }
                        $value_6 = $values_3;
                    }
                    if (is_null($value_5)) {
                        $value_6 = $value_5;
                    }
                    $values_2[] = $value_6;
                }
                $value_4 = $values_2;
            }
            if (is_null($data->{'SystemStatus'})) {
                $value_4 = $data->{'SystemStatus'};
            }
            $object->setSystemStatus($value_4);
        }
        if (property_exists($data, 'ExperimentalBuild')) {
            $object->setExperimentalBuild($data->{'ExperimentalBuild'});
        }
        if (property_exists($data, 'HttpProxy')) {
            $object->setHttpProxy($data->{'HttpProxy'});
        }
        if (property_exists($data, 'HttpsProxy')) {
            $object->setHttpsProxy($data->{'HttpsProxy'});
        }
        if (property_exists($data, 'ID')) {
            $object->setID($data->{'ID'});
        }
        if (property_exists($data, 'IPv4Forwarding')) {
            $object->setIPv4Forwarding($data->{'IPv4Forwarding'});
        }
        if (property_exists($data, 'Images')) {
            $object->setImages($data->{'Images'});
        }
        if (property_exists($data, 'IndexServerAddress')) {
            $object->setIndexServerAddress($data->{'IndexServerAddress'});
        }
        if (property_exists($data, 'InitPath')) {
            $object->setInitPath($data->{'InitPath'});
        }
        if (property_exists($data, 'InitSha1')) {
            $object->setInitSha1($data->{'InitSha1'});
        }
        if (property_exists($data, 'KernelMemory')) {
            $object->setKernelMemory($data->{'KernelMemory'});
        }
        if (property_exists($data, 'KernelVersion')) {
            $object->setKernelVersion($data->{'KernelVersion'});
        }
        if (property_exists($data, 'Labels')) {
            $value_8 = $data->{'Labels'};
            if (is_array($data->{'Labels'})) {
                $values_4 = [];
                foreach ($data->{'Labels'} as $value_9) {
                    $values_4[] = $value_9;
                }
                $value_8 = $values_4;
            }
            if (is_null($data->{'Labels'})) {
                $value_8 = $data->{'Labels'};
            }
            $object->setLabels($value_8);
        }
        if (property_exists($data, 'MemTotal')) {
            $object->setMemTotal($data->{'MemTotal'});
        }
        if (property_exists($data, 'MemoryLimit')) {
            $object->setMemoryLimit($data->{'MemoryLimit'});
        }
        if (property_exists($data, 'NCPU')) {
            $object->setNCPU($data->{'NCPU'});
        }
        if (property_exists($data, 'NEventsListener')) {
            $object->setNEventsListener($data->{'NEventsListener'});
        }
        if (property_exists($data, 'NFd')) {
            $object->setNFd($data->{'NFd'});
        }
        if (property_exists($data, 'NGoroutines')) {
            $object->setNGoroutines($data->{'NGoroutines'});
        }
        if (property_exists($data, 'Name')) {
            $object->setName($data->{'Name'});
        }
        if (property_exists($data, 'NoProxy')) {
            $object->setNoProxy($data->{'NoProxy'});
        }
        if (property_exists($data, 'OomKillDisable')) {
            $object->setOomKillDisable($data->{'OomKillDisable'});
        }
        if (property_exists($data, 'OSType')) {
            $object->setOSType($data->{'OSType'});
        }
        if (property_exists($data, 'OperatingSystem')) {
            $object->setOperatingSystem($data->{'OperatingSystem'});
        }
        if (property_exists($data, 'RegistryConfig')) {
            $object->setRegistryConfig($this->serializer->deserialize($data->{'RegistryConfig'}, 'Docker\\API\\Model\\RegistryConfig', 'raw', $context));
        }
        if (property_exists($data, 'SecurityOptions')) {
            $value_10 = $data->{'SecurityOptions'};
            if (is_array($data->{'SecurityOptions'})) {
                $values_5 = [];
                foreach ($data->{'SecurityOptions'} as $value_11) {
                    $values_5[] = $value_11;
                }
                $value_10 = $values_5;
            }
            if (is_null($data->{'SecurityOptions'})) {
                $value_10 = $data->{'SecurityOptions'};
            }
            $object->setSecurityOptions($value_10);
        }
        if (property_exists($data, 'SwapLimit')) {
            $object->setSwapLimit($data->{'SwapLimit'});
        }
        if (property_exists($data, 'SystemTime')) {
            $object->setSystemTime($data->{'SystemTime'});
        }
        if (property_exists($data, 'ServerVersion')) {
            $object->setServerVersion($data->{'ServerVersion'});
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = new \stdClass();
        if (null !== $object->getArchitecture()) {
            $data->{'Architecture'} = $object->getArchitecture();
        }
        if (null !== $object->getClusterStore()) {
            $data->{'ClusterStore'} = $object->getClusterStore();
        }
        if (null !== $object->getCgroupDriver()) {
            $data->{'CgroupDriver'} = $object->getCgroupDriver();
        }
        if (null !== $object->getContainers()) {
            $data->{'Containers'} = $object->getContainers();
        }
        if (null !== $object->getContainersRunning()) {
            $data->{'ContainersRunning'} = $object->getContainersRunning();
        }
        if (null !== $object->getContainersStopped()) {
            $data->{'ContainersStopped'} = $object->getContainersStopped();
        }
        if (null !== $object->getContainersPaused()) {
            $data->{'ContainersPaused'} = $object->getContainersPaused();
        }
        if (null !== $object->getCpuCfsPeriod()) {
            $data->{'CpuCfsPeriod'} = $object->getCpuCfsPeriod();
        }
        if (null !== $object->getCpuCfsQuota()) {
            $data->{'CpuCfsQuota'} = $object->getCpuCfsQuota();
        }
        if (null !== $object->getDebug()) {
            $data->{'Debug'} = $object->getDebug();
        }
        if (null !== $object->getDiscoveryBackend()) {
            $data->{'DiscoveryBackend'} = $object->getDiscoveryBackend();
        }
        if (null !== $object->getDockerRootDir()) {
            $data->{'DockerRootDir'} = $object->getDockerRootDir();
        }
        if (null !== $object->getDriver()) {
            $data->{'Driver'} = $object->getDriver();
        }
        $value = $object->getDriverStatus();
        if (is_array($object->getDriverStatus())) {
            $values = [];
            foreach ($object->getDriverStatus() as $value_1) {
                $value_2 = $value_1;
                if (is_array($value_1)) {
                    $values_1 = [];
                    foreach ($value_1 as $value_3) {
                        $values_1[] = $value_3;
                    }
                    $value_2 = $values_1;
                }
                if (is_null($value_1)) {
                    $value_2 = $value_1;
                }
                $values[] = $value_2;
            }
            $value = $values;
        }
        if (is_null($object->getDriverStatus())) {
            $value = $object->getDriverStatus();
        }
        $data->{'DriverStatus'} = $value;
        $value_4                = $object->getSystemStatus();
        if (is_array($object->getSystemStatus())) {
            $values_2 = [];
            foreach ($object->getSystemStatus() as $value_5) {
                $value_6 = $value_5;
                if (is_array($value_5)) {
                    $values_3 = [];
                    foreach ($value_5 as $value_7) {
                        $values_3[] = $value_7;
                    }
                    $value_6 = $values_3;
                }
                if (is_null($value_5)) {
                    $value_6 = $value_5;
                }
                $values_2[] = $value_6;
            }
            $value_4 = $values_2;
        }
        if (is_null($object->getSystemStatus())) {
            $value_4 = $object->getSystemStatus();
        }
        $data->{'SystemStatus'} = $value_4;
        if (null !== $object->getExperimentalBuild()) {
            $data->{'ExperimentalBuild'} = $object->getExperimentalBuild();
        }
        if (null !== $object->getHttpProxy()) {
            $data->{'HttpProxy'} = $object->getHttpProxy();
        }
        if (null !== $object->getHttpsProxy()) {
            $data->{'HttpsProxy'} = $object->getHttpsProxy();
        }
        if (null !== $object->getID()) {
            $data->{'ID'} = $object->getID();
        }
        if (null !== $object->getIPv4Forwarding()) {
            $data->{'IPv4Forwarding'} = $object->getIPv4Forwarding();
        }
        if (null !== $object->getImages()) {
            $data->{'Images'} = $object->getImages();
        }
        if (null !== $object->getIndexServerAddress()) {
            $data->{'IndexServerAddress'} = $object->getIndexServerAddress();
        }
        if (null !== $object->getInitPath()) {
            $data->{'InitPath'} = $object->getInitPath();
        }
        if (null !== $object->getInitSha1()) {
            $data->{'InitSha1'} = $object->getInitSha1();
        }
        if (null !== $object->getKernelMemory()) {
            $data->{'KernelMemory'} = $object->getKernelMemory();
        }
        if (null !== $object->getKernelVersion()) {
            $data->{'KernelVersion'} = $object->getKernelVersion();
        }
        $value_8 = $object->getLabels();
        if (is_array($object->getLabels())) {
            $values_4 = [];
            foreach ($object->getLabels() as $value_9) {
                $values_4[] = $value_9;
            }
            $value_8 = $values_4;
        }
        if (is_null($object->getLabels())) {
            $value_8 = $object->getLabels();
        }
        $data->{'Labels'} = $value_8;
        if (null !== $object->getMemTotal()) {
            $data->{'MemTotal'} = $object->getMemTotal();
        }
        if (null !== $object->getMemoryLimit()) {
            $data->{'MemoryLimit'} = $object->getMemoryLimit();
        }
        if (null !== $object->getNCPU()) {
            $data->{'NCPU'} = $object->getNCPU();
        }
        if (null !== $object->getNEventsListener()) {
            $data->{'NEventsListener'} = $object->getNEventsListener();
        }
        if (null !== $object->getNFd()) {
            $data->{'NFd'} = $object->getNFd();
        }
        if (null !== $object->getNGoroutines()) {
            $data->{'NGoroutines'} = $object->getNGoroutines();
        }
        if (null !== $object->getName()) {
            $data->{'Name'} = $object->getName();
        }
        if (null !== $object->getNoProxy()) {
            $data->{'NoProxy'} = $object->getNoProxy();
        }
        if (null !== $object->getOomKillDisable()) {
            $data->{'OomKillDisable'} = $object->getOomKillDisable();
        }
        if (null !== $object->getOSType()) {
            $data->{'OSType'} = $object->getOSType();
        }
        if (null !== $object->getOperatingSystem()) {
            $data->{'OperatingSystem'} = $object->getOperatingSystem();
        }
        if (null !== $object->getRegistryConfig()) {
            $data->{'RegistryConfig'} = $this->serializer->serialize($object->getRegistryConfig(), 'raw', $context);
        }
        $value_10 = $object->getSecurityOptions();
        if (is_array($object->getSecurityOptions())) {
            $values_5 = [];
            foreach ($object->getSecurityOptions() as $value_11) {
                $values_5[] = $value_11;
            }
            $value_10 = $values_5;
        }
        if (is_null($object->getSecurityOptions())) {
            $value_10 = $object->getSecurityOptions();
        }
        $data->{'SecurityOptions'} = $value_10;
        if (null !== $object->getSwapLimit()) {
            $data->{'SwapLimit'} = $object->getSwapLimit();
        }
        if (null !== $object->getSystemTime()) {
            $data->{'SystemTime'} = $object->getSystemTime();
        }
        if (null !== $object->getServerVersion()) {
            $data->{'ServerVersion'} = $object->getServerVersion();
        }

        return $data;
    }
}
