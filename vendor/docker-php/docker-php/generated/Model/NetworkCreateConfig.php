<?php

namespace Docker\API\Model;

class NetworkCreateConfig
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var bool
     */
    protected $checkDuplicate;
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
     * @var string[]
     */
    protected $options;
    /**
     * @var string[]
     */
    protected $labels;

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
     * @return bool
     */
    public function getCheckDuplicate()
    {
        return $this->checkDuplicate;
    }

    /**
     * @param bool $checkDuplicate
     *
     * @return self
     */
    public function setCheckDuplicate($checkDuplicate = null)
    {
        $this->checkDuplicate = $checkDuplicate;

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

    /**
     * @return string[]
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param string[] $labels
     *
     * @return self
     */
    public function setLabels(\ArrayObject $labels = null)
    {
        $this->labels = $labels;

        return $this;
    }
}
