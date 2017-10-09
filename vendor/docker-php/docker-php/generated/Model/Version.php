<?php

namespace Docker\API\Model;

class Version
{
    /**
     * @var string
     */
    protected $version;
    /**
     * @var string
     */
    protected $os;
    /**
     * @var string
     */
    protected $kernelVersion;
    /**
     * @var string
     */
    protected $goVersion;
    /**
     * @var string
     */
    protected $gitCommit;
    /**
     * @var string
     */
    protected $arch;
    /**
     * @var string
     */
    protected $apiVersion;
    /**
     * @var bool
     */
    protected $experimental;
    /**
     * @var string
     */
    protected $buildTime;

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return self
     */
    public function setVersion($version = null)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param string $os
     *
     * @return self
     */
    public function setOs($os = null)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * @return string
     */
    public function getKernelVersion()
    {
        return $this->kernelVersion;
    }

    /**
     * @param string $kernelVersion
     *
     * @return self
     */
    public function setKernelVersion($kernelVersion = null)
    {
        $this->kernelVersion = $kernelVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoVersion()
    {
        return $this->goVersion;
    }

    /**
     * @param string $goVersion
     *
     * @return self
     */
    public function setGoVersion($goVersion = null)
    {
        $this->goVersion = $goVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getGitCommit()
    {
        return $this->gitCommit;
    }

    /**
     * @param string $gitCommit
     *
     * @return self
     */
    public function setGitCommit($gitCommit = null)
    {
        $this->gitCommit = $gitCommit;

        return $this;
    }

    /**
     * @return string
     */
    public function getArch()
    {
        return $this->arch;
    }

    /**
     * @param string $arch
     *
     * @return self
     */
    public function setArch($arch = null)
    {
        $this->arch = $arch;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param string $apiVersion
     *
     * @return self
     */
    public function setApiVersion($apiVersion = null)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @return bool
     */
    public function getExperimental()
    {
        return $this->experimental;
    }

    /**
     * @param bool $experimental
     *
     * @return self
     */
    public function setExperimental($experimental = null)
    {
        $this->experimental = $experimental;

        return $this;
    }

    /**
     * @return string
     */
    public function getBuildTime()
    {
        return $this->buildTime;
    }

    /**
     * @param string $buildTime
     *
     * @return self
     */
    public function setBuildTime($buildTime = null)
    {
        $this->buildTime = $buildTime;

        return $this;
    }
}
