<?php

namespace Docker\API\Model;

class ErrorDetail
{
    /**
     * @var int
     */
    protected $code;
    /**
     * @var string
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
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }
}
