<?php

namespace Docker\API\Model;

class ContainerCreateResult
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string[]|null
     */
    protected $warnings;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setId($id = null)
    {
        $this->id = $id;

        return $this;
    }

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
