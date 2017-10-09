<?php

namespace Docker\API\Model;

class LogConfig
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string[]|null
     */
    protected $config;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string[]|null $config
     *
     * @return self
     */
    public function setConfig($config = null)
    {
        $this->config = $config;

        return $this;
    }
}
