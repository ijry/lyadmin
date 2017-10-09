<?php

namespace Docker\API\Model;

class NetworkContainer
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $endpointID;
    /**
     * @var string
     */
    protected $macAddress;
    /**
     * @var string
     */
    protected $iPv4Address;
    /**
     * @var string
     */
    protected $iPv6Address;

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
    public function getIPv4Address()
    {
        return $this->iPv4Address;
    }

    /**
     * @param string $iPv4Address
     *
     * @return self
     */
    public function setIPv4Address($iPv4Address = null)
    {
        $this->iPv4Address = $iPv4Address;

        return $this;
    }

    /**
     * @return string
     */
    public function getIPv6Address()
    {
        return $this->iPv6Address;
    }

    /**
     * @param string $iPv6Address
     *
     * @return self
     */
    public function setIPv6Address($iPv6Address = null)
    {
        $this->iPv6Address = $iPv6Address;

        return $this;
    }
}
