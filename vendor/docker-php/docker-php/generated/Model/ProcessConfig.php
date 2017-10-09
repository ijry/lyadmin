<?php

namespace Docker\API\Model;

class ProcessConfig
{
    /**
     * @var bool
     */
    protected $privileged;
    /**
     * @var string
     */
    protected $user;
    /**
     * @var bool
     */
    protected $tty;
    /**
     * @var string
     */
    protected $entrypoint;
    /**
     * @var string[]|null
     */
    protected $arguments;

    /**
     * @return bool
     */
    public function getPrivileged()
    {
        return $this->privileged;
    }

    /**
     * @param bool $privileged
     *
     * @return self
     */
    public function setPrivileged($privileged = null)
    {
        $this->privileged = $privileged;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return self
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTty()
    {
        return $this->tty;
    }

    /**
     * @param bool $tty
     *
     * @return self
     */
    public function setTty($tty = null)
    {
        $this->tty = $tty;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntrypoint()
    {
        return $this->entrypoint;
    }

    /**
     * @param string $entrypoint
     *
     * @return self
     */
    public function setEntrypoint($entrypoint = null)
    {
        $this->entrypoint = $entrypoint;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string[]|null $arguments
     *
     * @return self
     */
    public function setArguments($arguments = null)
    {
        $this->arguments = $arguments;

        return $this;
    }
}
