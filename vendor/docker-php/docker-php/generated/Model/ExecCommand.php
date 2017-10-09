<?php

namespace Docker\API\Model;

class ExecCommand
{
    /**
     * @var string
     */
    protected $iD;
    /**
     * @var bool
     */
    protected $running;
    /**
     * @var int
     */
    protected $exitCode;
    /**
     * @var ProcessConfig
     */
    protected $processConfig;
    /**
     * @var bool
     */
    protected $openStdin;
    /**
     * @var bool
     */
    protected $openStderr;
    /**
     * @var bool
     */
    protected $openStdout;
    /**
     * @var Container
     */
    protected $container;

    /**
     * @return string
     */
    public function getID()
    {
        return $this->iD;
    }

    /**
     * @param string $iD
     *
     * @return self
     */
    public function setID($iD = null)
    {
        $this->iD = $iD;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRunning()
    {
        return $this->running;
    }

    /**
     * @param bool $running
     *
     * @return self
     */
    public function setRunning($running = null)
    {
        $this->running = $running;

        return $this;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     *
     * @return self
     */
    public function setExitCode($exitCode = null)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * @return ProcessConfig
     */
    public function getProcessConfig()
    {
        return $this->processConfig;
    }

    /**
     * @param ProcessConfig $processConfig
     *
     * @return self
     */
    public function setProcessConfig(ProcessConfig $processConfig = null)
    {
        $this->processConfig = $processConfig;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOpenStdin()
    {
        return $this->openStdin;
    }

    /**
     * @param bool $openStdin
     *
     * @return self
     */
    public function setOpenStdin($openStdin = null)
    {
        $this->openStdin = $openStdin;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOpenStderr()
    {
        return $this->openStderr;
    }

    /**
     * @param bool $openStderr
     *
     * @return self
     */
    public function setOpenStderr($openStderr = null)
    {
        $this->openStderr = $openStderr;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOpenStdout()
    {
        return $this->openStdout;
    }

    /**
     * @param bool $openStdout
     *
     * @return self
     */
    public function setOpenStdout($openStdout = null)
    {
        $this->openStdout = $openStdout;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setContainer(Container $container = null)
    {
        $this->container = $container;

        return $this;
    }
}
