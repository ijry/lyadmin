<?php

namespace Docker\API\Model;

class Ulimit
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $soft;
    /**
     * @var int
     */
    protected $hard;

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
     * @return int
     */
    public function getSoft()
    {
        return $this->soft;
    }

    /**
     * @param int $soft
     *
     * @return self
     */
    public function setSoft($soft = null)
    {
        $this->soft = $soft;

        return $this;
    }

    /**
     * @return int
     */
    public function getHard()
    {
        return $this->hard;
    }

    /**
     * @param int $hard
     *
     * @return self
     */
    public function setHard($hard = null)
    {
        $this->hard = $hard;

        return $this;
    }
}
