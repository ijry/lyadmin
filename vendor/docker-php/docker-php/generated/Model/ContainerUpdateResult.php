<?php

namespace Docker\API\Model;

class ContainerUpdateResult
{
    /**
     * @var string[]|null
     */
    protected $warnings;

    /**
     * @return string[]|null
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @param string[]|null $warnings
     *
     * @return self
     */
    public function setWarnings($warnings = null)
    {
        $this->warnings = $warnings;

        return $this;
    }
}
