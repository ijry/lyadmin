<?php

namespace Docker\API\Model;

class Volume
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
     * @var string
     */
    protected $mountpoint;

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
     * @return string
     */
    public function getMountpoint()
    {
        return $this->mountpoint;
    }

    /**
     * @param string $mountpoint
     *
     * @return self
     */
    public function setMountpoint($mountpoint = null)
    {
        $this->mountpoint = $mountpoint;

        return $this;
    }
}
