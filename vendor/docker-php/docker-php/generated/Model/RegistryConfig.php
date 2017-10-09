<?php

namespace Docker\API\Model;

class RegistryConfig
{
    /**
     * @var Registry[]
     */
    protected $indexConfigs;
    /**
     * @var string[]|null
     */
    protected $insecureRegistryCIDRs;

    /**
     * @return Registry[]
     */
    public function getIndexConfigs()
    {
        return $this->indexConfigs;
    }

    /**
     * @param Registry[] $indexConfigs
     *
     * @return self
     */
    public function setIndexConfigs(\ArrayObject $indexConfigs = null)
    {
        $this->indexConfigs = $indexConfigs;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getInsecureRegistryCIDRs()
    {
        return $this->insecureRegistryCIDRs;
    }

    /**
     * @param string[]|null $insecureRegistryCIDRs
     *
     * @return self
     */
    public function setInsecureRegistryCIDRs($insecureRegistryCIDRs = null)
    {
        $this->insecureRegistryCIDRs = $insecureRegistryCIDRs;

        return $this;
    }
}
