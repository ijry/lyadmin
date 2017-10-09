<?php

namespace Docker\API\Model;

class AuthResult
{
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $identityToken;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityToken()
    {
        return $this->identityToken;
    }

    /**
     * @param string $identityToken
     *
     * @return self
     */
    public function setIdentityToken($identityToken = null)
    {
        $this->identityToken = $identityToken;

        return $this;
    }
}
