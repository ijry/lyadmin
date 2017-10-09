<?php

namespace Docker\API\Model;

class DeviceRate
{
    /**
     * @var string
     */
    protected $path;
    /**
     * @var int|string
     */
    protected $rate;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function setPath($path = null)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int|string $rate
     *
     * @return self
     */
    public function setRate($rate = null)
    {
        $this->rate = $rate;

        return $this;
    }
}
