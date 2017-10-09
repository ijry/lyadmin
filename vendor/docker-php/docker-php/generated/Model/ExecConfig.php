<?php

namespace Docker\API\Model;

class ExecConfig
{
    /**
     * @var bool
     */
    protected $attachStdin;
    /**
     * @var bool
     */
    protected $attachStdout;
    /**
     * @var bool
     */
    protected $attachStderr;
    /**
     * @var bool
     */
    protected $tty;
    /**
     * @var string[]|null
     */
    protected $cmd;

    /**
     * @return bool
     */
    public function getAttachStdin()
    {
        return $this->attachStdin;
    }

    /**
     * @param bool $attachStdin
     *
     * @return self
     */
    public function setAttachStdin($attachStdin = null)
    {
        $this->attachStdin = $attachStdin;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAttachStdout()
    {
        return $this->attachStdout;
    }

    /**
     * @param bool $attachStdout
     *
     * @return self
     */
    public function setAttachStdout($attachStdout = null)
    {
        $this->attachStdout = $attachStdout;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAttachStderr()
    {
        return $this->attachStderr;
    }

    /**
     * @param bool $attachStderr
     *
     * @return self
     */
    public function setAttachStderr($attachStderr = null)
    {
        $this->attachStderr = $attachStderr;

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
     * @return string[]|null
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @param string[]|null $cmd
     *
     * @return self
     */
    public function setCmd($cmd = null)
    {
        $this->cmd = $cmd;

        return $this;
    }
}
