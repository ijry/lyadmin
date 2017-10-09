<?php

namespace Docker\API\Model;

class IPAM
{
    /**
     * @var string
     */
    protected $driver;
    /**
     * @var IPAMConfig[]|null
     */
    protected $config;
    /**
     * @var string[]|null
     */
    protected $options;

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
     * @return IPAMConfig[]|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param IPAMConfig[]|null $config
     *
     * @return self
     */
    public function setConfig($config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string[]|null $options
     *
     * @return self
     */
    public function setOptions($options = null)
    {
        $this->options = $options;

        return $this;
    }
}
