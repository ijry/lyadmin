<?php

namespace Docker\API\Model;

class ContainerConnect
{
    /**
     * @var string
     */
    protected $container;
    /**
     * @var EndpointConfig[]
     */
    protected $endpointConfig;

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param string $container
     *
     * @return self
     */
    public function setContainer($container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return EndpointConfig[]
     */
    public function getEndpointConfig()
    {
        return $this->endpointConfig;
    }

    /**
     * @param EndpointConfig[] $endpointConfig
     *
     * @return self
     */
    public function setEndpointConfig(\ArrayObject $endpointConfig = null)
    {
        $this->endpointConfig = $endpointConfig;

        return $this;
    }
}
