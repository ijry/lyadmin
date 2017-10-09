<?php

namespace Docker\API\Model;

class ContainerConfig
{
    /**
     * @var string
     */
    protected $hostname;
    /**
     * @var string
     */
    protected $domainname;
    /**
     * @var string
     */
    protected $user;
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
     * @var bool
     */
    protected $openStdin;
    /**
     * @var bool
     */
    protected $stdinOnce;
    /**
     * @var string[]|null
     */
    protected $env;
    /**
     * @var string[]|string
     */
    protected $cmd;
    /**
     * @var string[]|string
     */
    protected $entrypoint;
    /**
     * @var string
     */
    protected $image;
    /**
     * @var string[]|null
     */
    protected $labels;
    /**
     * @var mixed[]|null
     */
    protected $volumes;
    /**
     * @var string
     */
    protected $workingDir;
    /**
     * @var bool
     */
    protected $networkDisabled;
    /**
     * @var string
     */
    protected $macAddress;
    /**
     * @var mixed[]|null
     */
    protected $exposedPorts;
    /**
     * @var string
     */
    protected $stopSignal;
    /**
     * @var HostConfig
     */
    protected $hostConfig;
    /**
     * @var NetworkingConfig
     */
    protected $networkingConfig;

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     *
     * @return self
     */
    public function setHostname($hostname = null)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomainname()
    {
        return $this->domainname;
    }

    /**
     * @param string $domainname
     *
     * @return self
     */
    public function setDomainname($domainname = null)
    {
        $this->domainname = $domainname;

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
    public function getStdinOnce()
    {
        return $this->stdinOnce;
    }

    /**
     * @param bool $stdinOnce
     *
     * @return self
     */
    public function setStdinOnce($stdinOnce = null)
    {
        $this->stdinOnce = $stdinOnce;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param string[]|null $env
     *
     * @return self
     */
    public function setEnv($env = null)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return string[]|string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @param string[]|string $cmd
     *
     * @return self
     */
    public function setCmd($cmd = null)
    {
        $this->cmd = $cmd;

        return $this;
    }

    /**
     * @return string[]|string
     */
    public function getEntrypoint()
    {
        return $this->entrypoint;
    }

    /**
     * @param string[]|string $entrypoint
     *
     * @return self
     */
    public function setEntrypoint($entrypoint = null)
    {
        $this->entrypoint = $entrypoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return self
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param string[]|null $labels
     *
     * @return self
     */
    public function setLabels($labels = null)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return mixed[]|null
     */
    public function getVolumes()
    {
        return $this->volumes;
    }

    /**
     * @param mixed[]|null $volumes
     *
     * @return self
     */
    public function setVolumes($volumes = null)
    {
        $this->volumes = $volumes;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorkingDir()
    {
        return $this->workingDir;
    }

    /**
     * @param string $workingDir
     *
     * @return self
     */
    public function setWorkingDir($workingDir = null)
    {
        $this->workingDir = $workingDir;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNetworkDisabled()
    {
        return $this->networkDisabled;
    }

    /**
     * @param bool $networkDisabled
     *
     * @return self
     */
    public function setNetworkDisabled($networkDisabled = null)
    {
        $this->networkDisabled = $networkDisabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getMacAddress()
    {
        return $this->macAddress;
    }

    /**
     * @param string $macAddress
     *
     * @return self
     */
    public function setMacAddress($macAddress = null)
    {
        $this->macAddress = $macAddress;

        return $this;
    }

    /**
     * @return mixed[]|null
     */
    public function getExposedPorts()
    {
        return $this->exposedPorts;
    }

    /**
     * @param mixed[]|null $exposedPorts
     *
     * @return self
     */
    public function setExposedPorts($exposedPorts = null)
    {
        $this->exposedPorts = $exposedPorts;

        return $this;
    }

    /**
     * @return string
     */
    public function getStopSignal()
    {
        return $this->stopSignal;
    }

    /**
     * @param string $stopSignal
     *
     * @return self
     */
    public function setStopSignal($stopSignal = null)
    {
        $this->stopSignal = $stopSignal;

        return $this;
    }

    /**
     * @return HostConfig
     */
    public function getHostConfig()
    {
        return $this->hostConfig;
    }

    /**
     * @param HostConfig $hostConfig
     *
     * @return self
     */
    public function setHostConfig(HostConfig $hostConfig = null)
    {
        $this->hostConfig = $hostConfig;

        return $this;
    }

    /**
     * @return NetworkingConfig
     */
    public function getNetworkingConfig()
    {
        return $this->networkingConfig;
    }

    /**
     * @param NetworkingConfig $networkingConfig
     *
     * @return self
     */
    public function setNetworkingConfig(NetworkingConfig $networkingConfig = null)
    {
        $this->networkingConfig = $networkingConfig;

        return $this;
    }
}
