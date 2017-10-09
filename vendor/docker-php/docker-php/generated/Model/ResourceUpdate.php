<?php

namespace Docker\API\Model;

class ResourceUpdate
{
    /**
     * @var int
     */
    protected $blkioWeight;
    /**
     * @var int
     */
    protected $cpuShares;
    /**
     * @var int
     */
    protected $cpuPeriod;
    /**
     * @var int
     */
    protected $cpuQuota;
    /**
     * @var string
     */
    protected $cpusetCpus;
    /**
     * @var string
     */
    protected $cpusetMems;
    /**
     * @var int
     */
    protected $memory;
    /**
     * @var int
     */
    protected $memorySwap;
    /**
     * @var int
     */
    protected $memoryReservation;
    /**
     * @var int
     */
    protected $kernelMemory;
    /**
     * @var RestartPolicy
     */
    protected $restartPolicy;

    /**
     * @return int
     */
    public function getBlkioWeight()
    {
        return $this->blkioWeight;
    }

    /**
     * @param int $blkioWeight
     *
     * @return self
     */
    public function setBlkioWeight($blkioWeight = null)
    {
        $this->blkioWeight = $blkioWeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpuShares()
    {
        return $this->cpuShares;
    }

    /**
     * @param int $cpuShares
     *
     * @return self
     */
    public function setCpuShares($cpuShares = null)
    {
        $this->cpuShares = $cpuShares;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpuPeriod()
    {
        return $this->cpuPeriod;
    }

    /**
     * @param int $cpuPeriod
     *
     * @return self
     */
    public function setCpuPeriod($cpuPeriod = null)
    {
        $this->cpuPeriod = $cpuPeriod;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpuQuota()
    {
        return $this->cpuQuota;
    }

    /**
     * @param int $cpuQuota
     *
     * @return self
     */
    public function setCpuQuota($cpuQuota = null)
    {
        $this->cpuQuota = $cpuQuota;

        return $this;
    }

    /**
     * @return string
     */
    public function getCpusetCpus()
    {
        return $this->cpusetCpus;
    }

    /**
     * @param string $cpusetCpus
     *
     * @return self
     */
    public function setCpusetCpus($cpusetCpus = null)
    {
        $this->cpusetCpus = $cpusetCpus;

        return $this;
    }

    /**
     * @return string
     */
    public function getCpusetMems()
    {
        return $this->cpusetMems;
    }

    /**
     * @param string $cpusetMems
     *
     * @return self
     */
    public function setCpusetMems($cpusetMems = null)
    {
        $this->cpusetMems = $cpusetMems;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param int $memory
     *
     * @return self
     */
    public function setMemory($memory = null)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemorySwap()
    {
        return $this->memorySwap;
    }

    /**
     * @param int $memorySwap
     *
     * @return self
     */
    public function setMemorySwap($memorySwap = null)
    {
        $this->memorySwap = $memorySwap;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemoryReservation()
    {
        return $this->memoryReservation;
    }

    /**
     * @param int $memoryReservation
     *
     * @return self
     */
    public function setMemoryReservation($memoryReservation = null)
    {
        $this->memoryReservation = $memoryReservation;

        return $this;
    }

    /**
     * @return int
     */
    public function getKernelMemory()
    {
        return $this->kernelMemory;
    }

    /**
     * @param int $kernelMemory
     *
     * @return self
     */
    public function setKernelMemory($kernelMemory = null)
    {
        $this->kernelMemory = $kernelMemory;

        return $this;
    }

    /**
     * @return RestartPolicy
     */
    public function getRestartPolicy()
    {
        return $this->restartPolicy;
    }

    /**
     * @param RestartPolicy $restartPolicy
     *
     * @return self
     */
    public function setRestartPolicy(RestartPolicy $restartPolicy = null)
    {
        $this->restartPolicy = $restartPolicy;

        return $this;
    }
}
