<?php

namespace Docker\API\Model;

class HostConfig
{
    /**
     * @var string[]|null
     */
    protected $binds;
    /**
     * @var string[]|null
     */
    protected $links;
    /**
     * @var string[]|null
     */
    protected $lxcConf;
    /**
     * @var int
     */
    protected $memory;
    /**
     * @var int
     */
    protected $memorySwap;
    /**
     * @var int
     */
    protected $cpuShares;
    /**
     * @var int
     */
    protected $cpuPeriod;
    /**
     * @var string
     */
    protected $cpusetCpus;
    /**
     * @var string
     */
    protected $cpusetMems;
    /**
     * @var int
     */
    protected $maximumIOps;
    /**
     * @var int
     */
    protected $maximumIOBps;
    /**
     * @var int
     */
    protected $blkioWeight;
    /**
     * @var DeviceWeight[]|null
     */
    protected $blkioWeightDevice;
    /**
     * @var DeviceRate[]|null
     */
    protected $blkioDeviceReadBps;
    /**
     * @var DeviceRate[]|null
     */
    protected $blkioDeviceReadIOps;
    /**
     * @var DeviceRate[]|null
     */
    protected $blkioDeviceWriteBps;
    /**
     * @var DeviceRate[]|null
     */
    protected $blkioDeviceWriteIOps;
    /**
     * @var int
     */
    protected $memorySwappiness;
    /**
     * @var bool
     */
    protected $oomKillDisable;
    /**
     * @var int
     */
    protected $oomScoreAdj;
    /**
     * @var int
     */
    protected $pidsLimit;
    /**
     * @var PortBinding[][]|null[]|null
     */
    protected $portBindings;
    /**
     * @var bool
     */
    protected $publishAllPorts;
    /**
     * @var bool
     */
    protected $privileged;
    /**
     * @var bool
     */
    protected $readonlyRootfs;
    /**
     * @var string[]|null
     */
    protected $sysctls;
    /**
     * @var string[]|null
     */
    protected $storageOpt;
    /**
     * @var string[]|null
     */
    protected $dns;
    /**
     * @var string[]|null
     */
    protected $dnsOptions;
    /**
     * @var string[]|null
     */
    protected $dnsSearch;
    /**
     * @var string[]|null
     */
    protected $extraHosts;
    /**
     * @var string[]|null
     */
    protected $volumesFrom;
    /**
     * @var string[]|null
     */
    protected $capAdd;
    /**
     * @var string[]|null
     */
    protected $capDrop;
    /**
     * @var string[]|null
     */
    protected $groupAdd;
    /**
     * @var RestartPolicy
     */
    protected $restartPolicy;
    /**
     * @var string
     */
    protected $usernsMode;
    /**
     * @var string
     */
    protected $networkMode;
    /**
     * @var Device[]|null
     */
    protected $devices;
    /**
     * @var Ulimit[]|null
     */
    protected $ulimits;
    /**
     * @var string[]|null
     */
    protected $securityOpt;
    /**
     * @var LogConfig
     */
    protected $logConfig;
    /**
     * @var string
     */
    protected $cgroupParent;
    /**
     * @var string
     */
    protected $volumeDriver;
    /**
     * @var int
     */
    protected $shmSize;

    /**
     * @return string[]|null
     */
    public function getBinds()
    {
        return $this->binds;
    }

    /**
     * @param string[]|null $binds
     *
     * @return self
     */
    public function setBinds($binds = null)
    {
        $this->binds = $binds;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param string[]|null $links
     *
     * @return self
     */
    public function setLinks($links = null)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLxcConf()
    {
        return $this->lxcConf;
    }

    /**
     * @param string[]|null $lxcConf
     *
     * @return self
     */
    public function setLxcConf($lxcConf = null)
    {
        $this->lxcConf = $lxcConf;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param int $memory
     *
     * @return self
     */
    public function setMemory($memory = null)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemorySwap()
    {
        return $this->memorySwap;
    }

    /**
     * @param int $memorySwap
     *
     * @return self
     */
    public function setMemorySwap($memorySwap = null)
    {
        $this->memorySwap = $memorySwap;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpuShares()
    {
        return $this->cpuShares;
    }

    /**
     * @param int $cpuShares
     *
     * @return self
     */
    public function setCpuShares($cpuShares = null)
    {
        $this->cpuShares = $cpuShares;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpuPeriod()
    {
        return $this->cpuPeriod;
    }

    /**
     * @param int $cpuPeriod
     *
     * @return self
     */
    public function setCpuPeriod($cpuPeriod = null)
    {
        $this->cpuPeriod = $cpuPeriod;

        return $this;
    }

    /**
     * @return string
     */
    public function getCpusetCpus()
    {
        return $this->cpusetCpus;
    }

    /**
     * @param string $cpusetCpus
     *
     * @return self
     */
    public function setCpusetCpus($cpusetCpus = null)
    {
        $this->cpusetCpus = $cpusetCpus;

        return $this;
    }

    /**
     * @return string
     */
    public function getCpusetMems()
    {
        return $this->cpusetMems;
    }

    /**
     * @param string $cpusetMems
     *
     * @return self
     */
    public function setCpusetMems($cpusetMems = null)
    {
        $this->cpusetMems = $cpusetMems;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumIOps()
    {
        return $this->maximumIOps;
    }

    /**
     * @param int $maximumIOps
     *
     * @return self
     */
    public function setMaximumIOps($maximumIOps = null)
    {
        $this->maximumIOps = $maximumIOps;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumIOBps()
    {
        return $this->maximumIOBps;
    }

    /**
     * @param int $maximumIOBps
     *
     * @return self
     */
    public function setMaximumIOBps($maximumIOBps = null)
    {
        $this->maximumIOBps = $maximumIOBps;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlkioWeight()
    {
        return $this->blkioWeight;
    }

    /**
     * @param int $blkioWeight
     *
     * @return self
     */
    public function setBlkioWeight($blkioWeight = null)
    {
        $this->blkioWeight = $blkioWeight;

        return $this;
    }

    /**
     * @return DeviceWeight[]|null
     */
    public function getBlkioWeightDevice()
    {
        return $this->blkioWeightDevice;
    }

    /**
     * @param DeviceWeight[]|null $blkioWeightDevice
     *
     * @return self
     */
    public function setBlkioWeightDevice($blkioWeightDevice = null)
    {
        $this->blkioWeightDevice = $blkioWeightDevice;

        return $this;
    }

    /**
     * @return DeviceRate[]|null
     */
    public function getBlkioDeviceReadBps()
    {
        return $this->blkioDeviceReadBps;
    }

    /**
     * @param DeviceRate[]|null $blkioDeviceReadBps
     *
     * @return self
     */
    public function setBlkioDeviceReadBps($blkioDeviceReadBps = null)
    {
        $this->blkioDeviceReadBps = $blkioDeviceReadBps;

        return $this;
    }

    /**
     * @return DeviceRate[]|null
     */
    public function getBlkioDeviceReadIOps()
    {
        return $this->blkioDeviceReadIOps;
    }

    /**
     * @param DeviceRate[]|null $blkioDeviceReadIOps
     *
     * @return self
     */
    public function setBlkioDeviceReadIOps($blkioDeviceReadIOps = null)
    {
        $this->blkioDeviceReadIOps = $blkioDeviceReadIOps;

        return $this;
    }

    /**
     * @return DeviceRate[]|null
     */
    public function getBlkioDeviceWriteBps()
    {
        return $this->blkioDeviceWriteBps;
    }

    /**
     * @param DeviceRate[]|null $blkioDeviceWriteBps
     *
     * @return self
     */
    public function setBlkioDeviceWriteBps($blkioDeviceWriteBps = null)
    {
        $this->blkioDeviceWriteBps = $blkioDeviceWriteBps;

        return $this;
    }

    /**
     * @return DeviceRate[]|null
     */
    public function getBlkioDeviceWriteIOps()
    {
        return $this->blkioDeviceWriteIOps;
    }

    /**
     * @param DeviceRate[]|null $blkioDeviceWriteIOps
     *
     * @return self
     */
    public function setBlkioDeviceWriteIOps($blkioDeviceWriteIOps = null)
    {
        $this->blkioDeviceWriteIOps = $blkioDeviceWriteIOps;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemorySwappiness()
    {
        return $this->memorySwappiness;
    }

    /**
     * @param int $memorySwappiness
     *
     * @return self
     */
    public function setMemorySwappiness($memorySwappiness = null)
    {
        $this->memorySwappiness = $memorySwappiness;

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
     * @return int
     */
    public function getOomScoreAdj()
    {
        return $this->oomScoreAdj;
    }

    /**
     * @param int $oomScoreAdj
     *
     * @return self
     */
    public function setOomScoreAdj($oomScoreAdj = null)
    {
        $this->oomScoreAdj = $oomScoreAdj;

        return $this;
    }

    /**
     * @return int
     */
    public function getPidsLimit()
    {
        return $this->pidsLimit;
    }

    /**
     * @param int $pidsLimit
     *
     * @return self
     */
    public function setPidsLimit($pidsLimit = null)
    {
        $this->pidsLimit = $pidsLimit;

        return $this;
    }

    /**
     * @return PortBinding[][]|null[]|null
     */
    public function getPortBindings()
    {
        return $this->portBindings;
    }

    /**
     * @param PortBinding[][]|null[]|null $portBindings
     *
     * @return self
     */
    public function setPortBindings($portBindings = null)
    {
        $this->portBindings = $portBindings;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPublishAllPorts()
    {
        return $this->publishAllPorts;
    }

    /**
     * @param bool $publishAllPorts
     *
     * @return self
     */
    public function setPublishAllPorts($publishAllPorts = null)
    {
        $this->publishAllPorts = $publishAllPorts;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPrivileged()
    {
        return $this->privileged;
    }

    /**
     * @param bool $privileged
     *
     * @return self
     */
    public function setPrivileged($privileged = null)
    {
        $this->privileged = $privileged;

        return $this;
    }

    /**
     * @return bool
     */
    public function getReadonlyRootfs()
    {
        return $this->readonlyRootfs;
    }

    /**
     * @param bool $readonlyRootfs
     *
     * @return self
     */
    public function setReadonlyRootfs($readonlyRootfs = null)
    {
        $this->readonlyRootfs = $readonlyRootfs;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getSysctls()
    {
        return $this->sysctls;
    }

    /**
     * @param string[]|null $sysctls
     *
     * @return self
     */
    public function setSysctls($sysctls = null)
    {
        $this->sysctls = $sysctls;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getStorageOpt()
    {
        return $this->storageOpt;
    }

    /**
     * @param string[]|null $storageOpt
     *
     * @return self
     */
    public function setStorageOpt($storageOpt = null)
    {
        $this->storageOpt = $storageOpt;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getDns()
    {
        return $this->dns;
    }

    /**
     * @param string[]|null $dns
     *
     * @return self
     */
    public function setDns($dns = null)
    {
        $this->dns = $dns;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getDnsOptions()
    {
        return $this->dnsOptions;
    }

    /**
     * @param string[]|null $dnsOptions
     *
     * @return self
     */
    public function setDnsOptions($dnsOptions = null)
    {
        $this->dnsOptions = $dnsOptions;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getDnsSearch()
    {
        return $this->dnsSearch;
    }

    /**
     * @param string[]|null $dnsSearch
     *
     * @return self
     */
    public function setDnsSearch($dnsSearch = null)
    {
        $this->dnsSearch = $dnsSearch;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getExtraHosts()
    {
        return $this->extraHosts;
    }

    /**
     * @param string[]|null $extraHosts
     *
     * @return self
     */
    public function setExtraHosts($extraHosts = null)
    {
        $this->extraHosts = $extraHosts;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getVolumesFrom()
    {
        return $this->volumesFrom;
    }

    /**
     * @param string[]|null $volumesFrom
     *
     * @return self
     */
    public function setVolumesFrom($volumesFrom = null)
    {
        $this->volumesFrom = $volumesFrom;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getCapAdd()
    {
        return $this->capAdd;
    }

    /**
     * @param string[]|null $capAdd
     *
     * @return self
     */
    public function setCapAdd($capAdd = null)
    {
        $this->capAdd = $capAdd;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getCapDrop()
    {
        return $this->capDrop;
    }

    /**
     * @param string[]|null $capDrop
     *
     * @return self
     */
    public function setCapDrop($capDrop = null)
    {
        $this->capDrop = $capDrop;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getGroupAdd()
    {
        return $this->groupAdd;
    }

    /**
     * @param string[]|null $groupAdd
     *
     * @return self
     */
    public function setGroupAdd($groupAdd = null)
    {
        $this->groupAdd = $groupAdd;

        return $this;
    }

    /**
     * @return RestartPolicy
     */
    public function getRestartPolicy()
    {
        return $this->restartPolicy;
    }

    /**
     * @param RestartPolicy $restartPolicy
     *
     * @return self
     */
    public function setRestartPolicy(RestartPolicy $restartPolicy = null)
    {
        $this->restartPolicy = $restartPolicy;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsernsMode()
    {
        return $this->usernsMode;
    }

    /**
     * @param string $usernsMode
     *
     * @return self
     */
    public function setUsernsMode($usernsMode = null)
    {
        $this->usernsMode = $usernsMode;

        return $this;
    }

    /**
     * @return string
     */
    public function getNetworkMode()
    {
        return $this->networkMode;
    }

    /**
     * @param string $networkMode
     *
     * @return self
     */
    public function setNetworkMode($networkMode = null)
    {
        $this->networkMode = $networkMode;

        return $this;
    }

    /**
     * @return Device[]|null
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param Device[]|null $devices
     *
     * @return self
     */
    public function setDevices($devices = null)
    {
        $this->devices = $devices;

        return $this;
    }

    /**
     * @return Ulimit[]|null
     */
    public function getUlimits()
    {
        return $this->ulimits;
    }

    /**
     * @param Ulimit[]|null $ulimits
     *
     * @return self
     */
    public function setUlimits($ulimits = null)
    {
        $this->ulimits = $ulimits;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getSecurityOpt()
    {
        return $this->securityOpt;
    }

    /**
     * @param string[]|null $securityOpt
     *
     * @return self
     */
    public function setSecurityOpt($securityOpt = null)
    {
        $this->securityOpt = $securityOpt;

        return $this;
    }

    /**
     * @return LogConfig
     */
    public function getLogConfig()
    {
        return $this->logConfig;
    }

    /**
     * @param LogConfig $logConfig
     *
     * @return self
     */
    public function setLogConfig(LogConfig $logConfig = null)
    {
        $this->logConfig = $logConfig;

        return $this;
    }

    /**
     * @return string
     */
    public function getCgroupParent()
    {
        return $this->cgroupParent;
    }

    /**
     * @param string $cgroupParent
     *
     * @return self
     */
    public function setCgroupParent($cgroupParent = null)
    {
        $this->cgroupParent = $cgroupParent;

        return $this;
    }

    /**
     * @return string
     */
    public function getVolumeDriver()
    {
        return $this->volumeDriver;
    }

    /**
     * @param string $volumeDriver
     *
     * @return self
     */
    public function setVolumeDriver($volumeDriver = null)
    {
        $this->volumeDriver = $volumeDriver;

        return $this;
    }

    /**
     * @return int
     */
    public function getShmSize()
    {
        return $this->shmSize;
    }

    /**
     * @param int $shmSize
     *
     * @return self
     */
    public function setShmSize($shmSize = null)
    {
        $this->shmSize = $shmSize;

        return $this;
    }
}
