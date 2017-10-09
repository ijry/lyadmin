<?php

namespace Docker\API\Model;

class VolumeConfig
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $driver;
    /**
     * @var string[]
     */
    protected $driverOpts;

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
     * @return string[]
     */
    public function getDriverOpts()
    {
        return $this->driverOpts;
    }

    /**
     * @param string[] $driverOpts
     *
     * @return self
     */
    public function setDriverOpts(\ArrayObject $driverOpts = null)
    {
        $this->driverOpts = $driverOpts;

        return $this;
    }
}
