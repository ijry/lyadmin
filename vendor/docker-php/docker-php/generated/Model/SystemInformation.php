<?php

namespace Docker\API\Model;

class SystemInformation
{
    /**
     * @var string
     */
    protected $architecture;
    /**
     * @var string
     */
    protected $clusterStore;
    /**
     * @var string
     */
    protected $cgroupDriver;
    /**
     * @var int
     */
    protected $containers;
    /**
     * @var int
     */
    protected $containersRunning;
    /**
     * @var int
     */
    protected $containersStopped;
    /**
     * @var int
     */
    protected $containersPaused;
    /**
     * @var bool
     */
    protected $cpuCfsPeriod;
    /**
     * @var bool
     */
    protected $cpuCfsQuota;
    /**
     * @var bool
     */
    protected $debug;
    /**
     * @var string
     */
    protected $discoveryBackend;
    /**
     * @var string
     */
    protected $dockerRootDir;
    /**
     * @var string
     */
    protected $driver;
    /**
     * @var string[][]|null[]|null
     */
    protected $driverStatus;
    /**
     * @var string[][]|null[]|null
     */
    protected $systemStatus;
    /**
     * @var bool
     */
    protected $experimentalBuild;
    /**
     * @var string
     */
    protected $httpProxy;
    /**
     * @var string
     */
    protected $httpsProxy;
    /**
     * @var string
     */
    protected $iD;
    /**
     * @var bool
     */
    protected $iPv4Forwarding;
    /**
     * @var int
     */
    protected $images;
    /**
     * @var string
     */
    protected $indexServerAddress;
    /**
     * @var string
     */
    protected $initPath;
    /**
     * @var string
     */
    protected $initSha1;
    /**
     * @var bool
     */
    protected $kernelMemory;
    /**
     * @var string
     */
    protected $kernelVersion;
    /**
     * @var string[]|null
     */
    protected $labels;
    /**
     * @var int
     */
    protected $memTotal;
    /**
     * @var bool
     */
    protected $memoryLimit;
    /**
     * @var int
     */
    protected $nCPU;
    /**
     * @var int
     */
    protected $nEventsListener;
    /**
     * @var int
     */
    protected $nFd;
    /**
     * @var int
     */
    protected $nGoroutines;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $noProxy;
    /**
     * @var bool
     */
    protected $oomKillDisable;
    /**
     * @var string
     */
    protected $oSType;
    /**
     * @var string
     */
    protected $operatingSystem;
    /**
     * @var RegistryConfig
     */
    protected $registryConfig;
    /**
     * @var string[]|null
     */
    protected $securityOptions;
    /**
     * @var bool
     */
    protected $swapLimit;
    /**
     * @var string
     */
    protected $systemTime;
    /**
     * @var string
     */
    protected $serverVersion;

    /**
     * @return string
     */
    public function getArchitecture()
    {
        return $this->architecture;
    }

    /**
     * @param string $architecture
     *
     * @return self
     */
    public function setArchitecture($architecture = null)
    {
        $this->architecture = $architecture;

        return $this;
    }

    /**
     * @return string
     */
    public function getClusterStore()
    {
        return $this->clusterStore;
    }

    /**
     * @param string $clusterStore
     *
     * @return self
     */
    public function setClusterStore($clusterStore = null)
    {
        $this->clusterStore = $clusterStore;

        return $this;
    }

    /**
     * @return string
     */
    public function getCgroupDriver()
    {
        return $this->cgroupDriver;
    }

    /**
     * @param string $cgroupDriver
     *
     * @return self
     */
    public function setCgroupDriver($cgroupDriver = null)
    {
        $this->cgroupDriver = $cgroupDriver;

        return $this;
    }

    /**
     * @return int
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param int $containers
     *
     * @return self
     */
    public function setContainers($containers = null)
    {
        $this->containers = $containers;

        return $this;
    }

    /**
     * @return int
     */
    public function getContainersRunning()
    {
        return $this->containersRunning;
    }

    /**
     * @param int $containersRunning
     *
     * @return self
     */
    public function setContainersRunning($containersRunning = null)
    {
        $this->containersRunning = $containersRunning;

        return $this;
    }

    /**
     * @return int
     */
    public function getContainersStopped()
    {
        return $this->containersStopped;
    }

    /**
     * @param int $containersStopped
     *
     * @return self
     */
    public function setContainersStopped($containersStopped = null)
    {
        $this->containersStopped = $containersStopped;

        return $this;
    }

    /**
     * @return int
     */
    public function getContainersPaused()
    {
        return $this->containersPaused;
    }

    /**
     * @param int $containersPaused
     *
     * @return self
     */
    public function setContainersPaused($containersPaused = null)
    {
        $this->containersPaused = $containersPaused;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCpuCfsPeriod()
    {
        return $this->cpuCfsPeriod;
    }

    /**
     * @param bool $cpuCfsPeriod
     *
     * @return self
     */
    public function setCpuCfsPeriod($cpuCfsPeriod = null)
    {
        $this->cpuCfsPeriod = $cpuCfsPeriod;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCpuCfsQuota()
    {
        return $this->cpuCfsQuota;
    }

    /**
     * @param bool $cpuCfsQuota
     *
     * @return self
     */
    public function setCpuCfsQuota($cpuCfsQuota = null)
    {
        $this->cpuCfsQuota = $cpuCfsQuota;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     *
     * @return self
     */
    public function setDebug($debug = null)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscoveryBackend()
    {
        return $this->discoveryBackend;
    }

    /**
     * @param string $discoveryBackend
     *
     * @return self
     */
    public function setDiscoveryBackend($discoveryBackend = null)
    {
        $this->discoveryBackend = $discoveryBackend;

        return $this;
    }

    /**
     * @return string
     */
    public function getDockerRootDir()
    {
        return $this->dockerRootDir;
    }

    /**
     * @param string $dockerRootDir
     *
     * @return self
     */
    public function setDockerRootDir($dockerRootDir = null)
    {
        $this->dockerRootDir = $dockerRootDir;

        return $this;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     *
     * @return self
     */
    public function setDriver($driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string[][]|null[]|null
     */
    public function getDriverStatus()
    {
        return $this->driverStatus;
    }

    /**
     * @param string[][]|null[]|null $driverStatus
     *
     * @return self
     */
    public function setDriverStatus($driverStatus = null)
    {
        $this->driverStatus = $driverStatus;

        return $this;
    }

    /**
     * @return string[][]|null[]|null
     */
    public function getSystemStatus()
    {
        return $this->systemStatus;
    }

    /**
     * @param string[][]|null[]|null $systemStatus
     *
     * @return self
     */
    public function setSystemStatus($systemStatus = null)
    {
        $this->systemStatus = $systemStatus;

        return $this;
    }

    /**
     * @return bool
     */
    public function getExperimentalBuild()
    {
        return $this->experimentalBuild;
    }

    /**
     * @param bool $experimentalBuild
     *
     * @return self
     */
    public function setExperimentalBuild($experimentalBuild = null)
    {
        $this->experimentalBuild = $experimentalBuild;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpProxy()
    {
        return $this->httpProxy;
    }

    /**
     * @param string $httpProxy
     *
     * @return self
     */
    public function setHttpProxy($httpProxy = null)
    {
        $this->httpProxy = $httpProxy;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpsProxy()
    {
        return $this->httpsProxy;
    }

    /**
     * @param string $httpsProxy
     *
     * @return self
     */
    public function setHttpsProxy($httpsProxy = null)
    {
        $this->httpsProxy = $httpsProxy;

        return $this;
    }

    /**
     * @return string
     */
    public function getID()
    {
        return $this->iD;
    }

    /**
     * @param string $iD
     *
     * @return self
     */
    public function setID($iD = null)
    {
        $this->iD = $iD;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIPv4Forwarding()
    {
        return $this->iPv4Forwarding;
    }

    /**
     * @param bool $iPv4Forwarding
     *
     * @return self
     */
    public function setIPv4Forwarding($iPv4Forwarding = null)
    {
        $this->iPv4Forwarding = $iPv4Forwarding;

        return $this;
    }

    /**
     * @return int
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param int $images
     *
     * @return self
     */
    public function setImages($images = null)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return string
     */
    public function getIndexServerAddress()
    {
        return $this->indexServerAddress;
    }

    /**
     * @param string $indexServerAddress
     *
     * @return self
     */
    public function setIndexServerAddress($indexServerAddress = null)
    {
        $this->indexServerAddress = $indexServerAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getInitPath()
    {
        return $this->initPath;
    }

    /**
     * @param string $initPath
     *
     * @return self
     */
    public function setInitPath($initPath = null)
    {
        $this->initPath = $initPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getInitSha1()
    {
        return $this->initSha1;
    }

    /**
     * @param string $initSha1
     *
     * @return self
     */
    public function setInitSha1($initSha1 = null)
    {
        $this->initSha1 = $initSha1;

        return $this;
    }

    /**
     * @return bool
     */
    public function getKernelMemory()
    {
        return $this->kernelMemory;
    }

    /**
     * @param bool $kernelMemory
     *
     * @return self
     */
    public function setKernelMemory($kernelMemory = null)
    {
        $this->kernelMemory = $kernelMemory;

        return $this;
    }

    /**
     * @return string
     */
    public function getKernelVersion()
    {
        return $this->kernelVersion;
    }

    /**
     * @param string $kernelVersion
     *
     * @return self
     */
    public function setKernelVersion($kernelVersion = null)
    {
        $this->kernelVersion = $kernelVersion;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param string[]|null $labels
     *
     * @return self
     */
    public function setLabels($labels = null)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemTotal()
    {
        return $this->memTotal;
    }

    /**
     * @param int $memTotal
     *
     * @return self
     */
    public function setMemTotal($memTotal = null)
    {
        $this->memTotal = $memTotal;

        return $this;
    }

    /**
     * @return bool
     */
    public function getMemoryLimit()
    {
        return $this->memoryLimit;
    }

    /**
     * @param bool $memoryLimit
     *
     * @return self
     */
    public function setMemoryLimit($memoryLimit = null)
    {
        $this->memoryLimit = $memoryLimit;

        return $this;
    }

    /**
     * @return int
     */
    public function getNCPU()
    {
        return $this->nCPU;
    }

    /**
     * @param int $nCPU
     *
     * @return self
     */
    public function setNCPU($nCPU = null)
    {
        $this->nCPU = $nCPU;

        return $this;
    }

    /**
     * @return int
     */
    public function getNEventsListener()
    {
        return $this->nEventsListener;
    }

    /**
     * @param int $nEventsListener
     *
     * @return self
     */
    public function setNEventsListener($nEventsListener = null)
    {
        $this->nEventsListener = $nEventsListener;

        return $this;
    }

    /**
     * @return int
     */
    public function getNFd()
    {
        return $this->nFd;
    }

    /**
     * @param int $nFd
     *
     * @return self
     */
    public function setNFd($nFd = null)
    {
        $this->nFd = $nFd;

        return $this;
    }

    /**
     * @return int
     */
    public function getNGoroutines()
    {
        return $this->nGoroutines;
    }

    /**
     * @param int $nGoroutines
     *
     * @return self
     */
    public function setNGoroutines($nGoroutines = null)
    {
        $this->nGoroutines = $nGoroutines;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNoProxy()
    {
        return $this->noProxy;
    }

    /**
     * @param string $noProxy
     *
     * @return self
     */
    public function setNoProxy($noProxy = null)
    {
        $this->noProxy = $noProxy;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOomKillDisable()
    {
        return $this->oomKillDisable;
    }

    /**
     * @param bool $oomKillDisable
     *
     * @return self
     */
    public function setOomKillDisable($oomKillDisable = null)
    {
        $this->oomKillDisable = $oomKillDisable;

        return $this;
    }

    /**
     * @return string
     */
    public function getOSType()
    {
        return $this->oSType;
    }

    /**
     * @param string $oSType
     *
     * @return self
     */
    public function setOSType($oSType = null)
    {
        $this->oSType = $oSType;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperatingSystem()
    {
        return $this->operatingSystem;
    }

    /**
     * @param string $operatingSystem
     *
     * @return self
     */
    public function setOperatingSystem($operatingSystem = null)
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    /**
     * @return RegistryConfig
     */
    public function getRegistryConfig()
    {
        return $this->registryConfig;
    }

    /**
     * @param RegistryConfig $registryConfig
     *
     * @return self
     */
    public function setRegistryConfig(RegistryConfig $registryConfig = null)
    {
        $this->registryConfig = $registryConfig;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getSecurityOptions()
    {
        return $this->securityOptions;
    }

    /**
     * @param string[]|null $securityOptions
     *
     * @return self
     */
    public function setSecurityOptions($securityOptions = null)
    {
        $this->securityOptions = $securityOptions;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSwapLimit()
    {
        return $this->swapLimit;
    }

    /**
     * @param bool $swapLimit
     *
     * @return self
     */
    public function setSwapLimit($swapLimit = null)
    {
        $this->swapLimit = $swapLimit;

        return $this;
    }

    /**
     * @return string
     */
    public function getSystemTime()
    {
        return $this->systemTime;
    }

    /**
     * @param string $systemTime
     *
     * @return self
     */
    public function setSystemTime($systemTime = null)
    {
        $this->systemTime = $systemTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getServerVersion()
    {
        return $this->serverVersion;
    }

    /**
     * @param string $serverVersion
     *
     * @return self
     */
    public function setServerVersion($serverVersion = null)
    {
        $this->serverVersion = $serverVersion;

        return $this;
    }
}
