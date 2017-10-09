<?php

namespace Docker\API\Model;

class ContainerNetwork
{
    /**
     * @var string
     */
    protected $networkID;
    /**
     * @var string
     */
    protected $endpointID;
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
    protected $iPv6Gateway;
    /**
     * @var string
     */
    protected $globalIPv6Address;
    /**
     * @var int
     */
    protected $globalIPv6PrefixLen;
    /**
     * @var string
     */
    protected $macAddress;

    /**
     * @return string
     */
    public function getNetworkID()
    {
        return $this->networkID;
    }

    /**
     * @param string $networkID
     *
     * @return self
     */
    public function setNetworkID($networkID = null)
    {
        $this->networkID = $networkID;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpointID()
    {
        return $this->endpointID;
    }

    /**
     * @param string $endpointID
     *
     * @return self
     */
    public function setEndpointID($endpointID = null)
    {
        $this->endpointID = $endpointID;

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
    public function getIPv6Gateway()
    {
        return $this->iPv6Gateway;
    }

    /**
     * @param string $iPv6Gateway
     *
     * @return self
     */
    public function setIPv6Gateway($iPv6Gateway = null)
    {
        $this->iPv6Gateway = $iPv6Gateway;

        return $this;
    }

    /**
     * @return string
     */
    public function getGlobalIPv6Address()
    {
        return $this->globalIPv6Address;
    }

    /**
     * @param string $globalIPv6Address
     *
     * @return self
     */
    public function setGlobalIPv6Address($globalIPv6Address = null)
    {
        $this->globalIPv6Address = $globalIPv6Address;

        return $this;
    }

    /**
     * @return int
     */
    public function getGlobalIPv6PrefixLen()
    {
        return $this->globalIPv6PrefixLen;
    }

    /**
     * @param int $globalIPv6PrefixLen
     *
     * @return self
     */
    public function setGlobalIPv6PrefixLen($globalIPv6PrefixLen = null)
    {
        $this->globalIPv6PrefixLen = $globalIPv6PrefixLen;

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
}
