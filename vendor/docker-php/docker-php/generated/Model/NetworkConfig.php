<?php

namespace Docker\API\Model;

class NetworkConfig
{
    /**
     * @var string
     */
    protected $bridge;
    /**
     * @var string
     */
    protected $gateway;
    /**
     * @var string
     */
    protected $iPAddress;
    /**
     * @var int
     */
    protected $iPPrefixLen;
    /**
     * @var string
     */
    protected $macAddress;
    /**
     * @var string
     */
    protected $portMapping;
    /**
     * @var ContainerNetwork[]
     */
    protected $networks;
    /**
     * @var PortBinding[][]|null[]|null
     */
    protected $ports;

    /**
     * @return string
     */
    public function getBridge()
    {
        return $this->bridge;
    }

    /**
     * @param string $bridge
     *
     * @return self
     */
    public function setBridge($bridge = null)
    {
        $this->bridge = $bridge;

        return $this;
    }

    /**
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param string $gateway
     *
     * @return self
     */
    public function setGateway($gateway = null)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * @return string
     */
    public function getIPAddress()
    {
        return $this->iPAddress;
    }

    /**
     * @param string $iPAddress
     *
     * @return self
     */
    public function setIPAddress($iPAddress = null)
    {
        $this->iPAddress = $iPAddress;

        return $this;
    }

    /**
     * @return int
     */
    public function getIPPrefixLen()
    {
        return $this->iPPrefixLen;
    }

    /**
     * @param int $iPPrefixLen
     *
     * @return self
     */
    public function setIPPrefixLen($iPPrefixLen = null)
    {
        $this->iPPrefixLen = $iPPrefixLen;

        return $this;
    }

    /**
     * @return string
     */
    public function getMacAddress()
    {
        return $this->macAddress;
    }

    /**
     * @param string $macAddress
     *
     * @return self
     */
    public function setMacAddress($macAddress = null)
    {
        $this->macAddress = $macAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getPortMapping()
    {
        return $this->portMapping;
    }

    /**
     * @param string $portMapping
     *
     * @return self
     */
    public function setPortMapping($portMapping = null)
    {
        $this->portMapping = $portMapping;

        return $this;
    }

    /**
     * @return ContainerNetwork[]
     */
    public function getNetworks()
    {
        return $this->networks;
    }

    /**
     * @param ContainerNetwork[] $networks
     *
     * @return self
     */
    public function setNetworks(\ArrayObject $networks = null)
    {
        $this->networks = $networks;

        return $this;
    }

    /**
     * @return PortBinding[][]|null[]|null
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * @param PortBinding[][]|null[]|null $ports
     *
     * @return self
     */
    public function setPorts($ports = null)
    {
        $this->ports = $ports;

        return $this;
    }
}
