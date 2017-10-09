<?php

namespace Docker\API\Model;

class Network
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $scope;
    /**
     * @var string
     */
    protected $driver;
    /**
     * @var bool
     */
    protected $enableIPv6;
    /**
     * @var IPAM
     */
    protected $iPAM;
    /**
     * @var bool
     */
    protected $internal;
    /**
     * @var NetworkContainer[]
     */
    protected $containers;
    /**
     * @var string[]
     */
    protected $options;

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
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     *
     * @return self
     */
    public function setScope($scope = null)
    {
        $this->scope = $scope;

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
     * @return bool
     */
    public function getEnableIPv6()
    {
        return $this->enableIPv6;
    }

    /**
     * @param bool $enableIPv6
     *
     * @return self
     */
    public function setEnableIPv6($enableIPv6 = null)
    {
        $this->enableIPv6 = $enableIPv6;

        return $this;
    }

    /**
     * @return IPAM
     */
    public function getIPAM()
    {
        return $this->iPAM;
    }

    /**
     * @param IPAM $iPAM
     *
     * @return self
     */
    public function setIPAM(IPAM $iPAM = null)
    {
        $this->iPAM = $iPAM;

        return $this;
    }

    /**
     * @return bool
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * @param bool $internal
     *
     * @return self
     */
    public function setInternal($internal = null)
    {
        $this->internal = $internal;

        return $this;
    }

    /**
     * @return NetworkContainer[]
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param NetworkContainer[] $containers
     *
     * @return self
     */
    public function setContainers(\ArrayObject $containers = null)
    {
        $this->containers = $containers;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string[] $options
     *
     * @return self
     */
    public function setOptions(\ArrayObject $options = null)
    {
        $this->options = $options;

        return $this;
    }
}
