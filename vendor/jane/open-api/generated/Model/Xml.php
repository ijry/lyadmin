<?php

namespace Joli\Jane\OpenApi\Model;

class Xml
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $namespace;
    /**
     * @var string
     */
    protected $prefix;
    /**
     * @var bool
     */
    protected $attribute;
    /**
     * @var bool
     */
    protected $wrapped;

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
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     *
     * @return self
     */
    public function setNamespace($namespace = null)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return self
     */
    public function setPrefix($prefix = null)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param bool $attribute
     *
     * @return self
     */
    public function setAttribute($attribute = null)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWrapped()
    {
        return $this->wrapped;
    }

    /**
     * @param bool $wrapped
     *
     * @return self
     */
    public function setWrapped($wrapped = null)
    {
        $this->wrapped = $wrapped;

        return $this;
    }
}
