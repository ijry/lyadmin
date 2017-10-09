<?php

namespace Docker\API\Model;

class NetworkCreateResult
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $warning;

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
     * @return string
     */
    public function getWarning()
    {
        return $this->warning;
    }

    /**
     * @param string $warning
     *
     * @return self
     */
    public function setWarning($warning = null)
    {
        $this->warning = $warning;

        return $this;
    }
}
