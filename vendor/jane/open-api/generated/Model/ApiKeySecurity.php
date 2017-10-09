<?php

namespace Joli\Jane\OpenApi\Model;

class ApiKeySecurity
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $in;
    /**
     * @var string
     */
    protected $description;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getIn()
    {
        return $this->in;
    }

    /**
     * @param string $in
     *
     * @return self
     */
    public function setIn($in = null)
    {
        $this->in = $in;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }
}
