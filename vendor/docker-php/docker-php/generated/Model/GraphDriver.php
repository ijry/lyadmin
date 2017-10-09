<?php

namespace Docker\API\Model;

class GraphDriver
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var mixed
     */
    protected $data;

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
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data = null)
    {
        $this->data = $data;

        return $this;
    }
}
