<?php

namespace Docker\API\Model;

class ContainerWait
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode = null)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
