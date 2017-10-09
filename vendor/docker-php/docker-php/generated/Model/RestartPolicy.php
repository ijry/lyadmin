<?php

namespace Docker\API\Model;

class RestartPolicy
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $maximumRetryCount;

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
    public function getMaximumRetryCount()
    {
        return $this->maximumRetryCount;
    }

    /**
     * @param int $maximumRetryCount
     *
     * @return self
     */
    public function setMaximumRetryCount($maximumRetryCount = null)
    {
        $this->maximumRetryCount = $maximumRetryCount;

        return $this;
    }
}
