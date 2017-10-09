<?php

namespace Docker\API\Model;

class ContainerInfo
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string[]|null
     */
    protected $names;
    /**
     * @var string
     */
    protected $image;
    /**
     * @var string
     */
    protected $imageID;
    /**
     * @var string
     */
    protected $command;
    /**
     * @var int
     */
    protected $created;
    /**
     * @var string
     */
    protected $state;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var Port[]|null
     */
    protected $ports;
    /**
     * @var string[]|null
     */
    protected $labels;
    /**
     * @var int
     */
    protected $sizeRw;
    /**
     * @var int
     */
    protected $sizeRootFs;
    /**
     * @var HostConfig
     */
    protected $hostConfig;
    /**
     * @var NetworkConfig
     */
    protected $networkSettings;
    /**
     * @var Mount[]|null
     */
    protected $mounts;

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
     * @return string[]|null
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param string[]|null $names
     *
     * @return self
     */
    public function setNames($names = null)
    {
        $this->names = $names;

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
    public function getImageID()
    {
        return $this->imageID;
    }

    /**
     * @param string $imageID
     *
     * @return self
     */
    public function setImageID($imageID = null)
    {
        $this->imageID = $imageID;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     *
     * @return self
     */
    public function setCommand($command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param int $created
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
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return self
     */
    public function setState($state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Port[]|null
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * @param Port[]|null $ports
     *
     * @return self
     */
    public function setPorts($ports = null)
    {
        $this->ports = $ports;

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
    public function getSizeRw()
    {
        return $this->sizeRw;
    }

    /**
     * @param int $sizeRw
     *
     * @return self
     */
    public function setSizeRw($sizeRw = null)
    {
        $this->sizeRw = $sizeRw;

        return $this;
    }

    /**
     * @return int
     */
    public function getSizeRootFs()
    {
        return $this->sizeRootFs;
    }

    /**
     * @param int $sizeRootFs
     *
     * @return self
     */
    public function setSizeRootFs($sizeRootFs = null)
    {
        $this->sizeRootFs = $sizeRootFs;

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
