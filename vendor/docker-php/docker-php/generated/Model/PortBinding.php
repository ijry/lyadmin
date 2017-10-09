<?php

namespace Docker\API\Model;

class PortBinding
{
    /**
     * @var string
     */
    protected $hostPort;
    /**
     * @var string
     */
    protected $hostIp;

    /**
     * @return string
     */
    public function getHostPort()
    {
        return $this->hostPort;
    }

    /**
     * @param string $hostPort
     *
     * @return self
     */
    public function setHostPort($hostPort = null)
    {
        $this->hostPort = $hostPort;

        return $this;
    }

    /**
     * @return string
     */
    public function getHostIp()
    {
        return $this->hostIp;
    }

    /**
     * @param string $hostIp
     *
     * @return self
     */
    public function setHostIp($hostIp = null)
    {
        $this->hostIp = $hostIp;

        return $this;
    }
}
