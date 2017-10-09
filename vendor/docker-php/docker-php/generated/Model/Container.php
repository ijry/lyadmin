<?php

namespace Docker\API\Model;

class Container
{
    /**
     * @var string
     */
    protected $appArmorProfile;
    /**
     * @var string[]|null
     */
    protected $args;
    /**
     * @var ContainerConfig
     */
    protected $config;
    /**
     * @var string
     */
    protected $created;
    /**
     * @var string
     */
    protected $driver;
    /**
     * @var string
     */
    protected $execDriver;
    /**
     * @var string
     */
    protected $execIDs;
    /**
     * @var HostConfig
     */
    protected $hostConfig;
    /**
     * @var string
     */
    protected $hostnamePath;
    /**
     * @var string
     */
    protected $hostsPath;
    /**
     * @var string
     */
    protected $logPath;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $image;
    /**
     * @var string
     */
    protected $mountLabel;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var NetworkConfig
     */
    protected $networkSettings;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $processLabel;
    /**
     * @var string
     */
    protected $resolvConfPath;
    /**
     * @var int
     */
    protected $restartCount;
    /**
     * @var ContainerState
     */
    protected $state;
    /**
     * @var Mount[]|null
     */
    protected $mounts;

    /**
     * @return string
     */
    public function getAppArmorProfile()
    {
        return $this->appArmorProfile;
    }

    /**
     * @param string $appArmorProfile
     *
     * @return self
     */
    public function setAppArmorProfile($appArmorProfile = null)
    {
        $this->appArmorProfile = $appArmorProfile;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param string[]|null $args
     *
     * @return self
     */
    public function setArgs($args = null)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * @return ContainerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param ContainerConfig $config
     *
     * @return self
     */
    public function setConfig(ContainerConfig $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $created
     *
     * @return self
     */
    public function setCreated($created = null)
    {
        $this->created = $created;

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
     * @return string
     */
    public function getExecDriver()
    {
        return $this->execDriver;
    }

    /**
     * @param string $execDriver
     *
     * @return self
     */
    public function setExecDriver($execDriver = null)
    {
        $this->execDriver = $execDriver;

        return $this;
    }

    /**
     * @return string
     */
    public function getExecIDs()
    {
        return $this->execIDs;
    }

    /**
     * @param string $execIDs
     *
     * @return self
     */
    public function setExecIDs($execIDs = null)
    {
        $this->execIDs = $execIDs;

        return $this;
    }

    /**
     * @return HostConfig
     */
    public function getHostConfig()
    {
        return $this->hostConfig;
    }

    /**
     * @param HostConfig $hostConfig
     *
     * @return self
     */
    public function setHostConfig(HostConfig $hostConfig = null)
    {
        $this->hostConfig = $hostConfig;

        return $this;
    }

    /**
     * @return string
     */
    public function getHostnamePath()
    {
        return $this->hostnamePath;
    }

    /**
     * @param string $hostnamePath
     *
     * @return self
     */
    public function setHostnamePath($hostnamePath = null)
    {
        $this->hostnamePath = $hostnamePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getHostsPath()
    {
        return $this->hostsPath;
    }

    /**
     * @param string $hostsPath
     *
     * @return self
     */
    public function setHostsPath($hostsPath = null)
    {
        $this->hostsPath = $hostsPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * @param string $logPath
     *
     * @return self
     */
    public function setLogPath($logPath = null)
    {
        $this->logPath = $logPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return self
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getMountLabel()
    {
        return $this->mountLabel;
    }

    /**
     * @param string $mountLabel
     *
     * @return self
     */
    public function setMountLabel($mountLabel = null)
    {
        $this->mountLabel = $mountLabel;

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
     * @return NetworkConfig
     */
    public function getNetworkSettings()
    {
        return $this->networkSettings;
    }

    /**
     * @param NetworkConfig $networkSettings
     *
     * @return self
     */
    public function setNetworkSettings(NetworkConfig $networkSettings = null)
    {
        $this->networkSettings = $networkSettings;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function setPath($path = null)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getProcessLabel()
    {
        return $this->processLabel;
    }

    /**
     * @param string $processLabel
     *
     * @return self
     */
    public function setProcessLabel($processLabel = null)
    {
        $this->processLabel = $processLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getResolvConfPath()
    {
        return $this->resolvConfPath;
    }

    /**
     * @param string $resolvConfPath
     *
     * @return self
     */
    public function setResolvConfPath($resolvConfPath = null)
    {
        $this->resolvConfPath = $resolvConfPath;

        return $this;
    }

    /**
     * @return int
     */
    public function getRestartCount()
    {
        return $this->restartCount;
    }

    /**
     * @param int $restartCount
     *
     * @return self
     */
    public function setRestartCount($restartCount = null)
    {
        $this->restartCount = $restartCount;

        return $this;
    }

    /**
     * @return ContainerState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param ContainerState $state
     *
     * @return self
     */
    public function setState(ContainerState $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Mount[]|null
     */
    public function getMounts()
    {
        return $this->mounts;
    }

    /**
     * @param Mount[]|null $mounts
     *
     * @return self
     */
    public function setMounts($mounts = null)
    {
        $this->mounts = $mounts;

        return $this;
    }
}
