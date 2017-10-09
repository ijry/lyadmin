<?php

namespace Docker\API\Model;

class ProgressDetail
{
    /**
     * @var int
     */
    protected $code;
    /**
     * @var int
     */
    protected $message;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return self
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param int $message
     *
     * @return self
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }
}
