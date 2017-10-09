<?php

namespace Docker\API\Model;

class Mount
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $source;
    /**
     * @var string
     */
    protected $destination;
    /**
     * @var string
     */
    protected $driver;
    /**
     * @var string
     */
    protected $mode;
    /**
     * @var bool
     */
    protected $rW;
    /**
     * @var string
     */
    protected $propagation;

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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return self
     */
    public function setSource($source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     *
     * @return self
     */
    public function setDestination($destination = null)
    {
        $this->destination = $destination;

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
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return self
     */
    public function setMode($mode = null)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRW()
    {
        return $this->rW;
    }

    /**
     * @param bool $rW
     *
     * @return self
     */
    public function setRW($rW = null)
    {
        $this->rW = $rW;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropagation()
    {
        return $this->propagation;
    }

    /**
     * @param string $propagation
     *
     * @return self
     */
    public function setPropagation($propagation = null)
    {
        $this->propagation = $propagation;

        return $this;
    }
}
