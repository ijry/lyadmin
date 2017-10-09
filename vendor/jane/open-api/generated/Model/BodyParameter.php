<?php

namespace Joli\Jane\OpenApi\Model;

class BodyParameter
{
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $in;
    /**
     * @var bool
     */
    protected $required;
    /**
     * @var Schema
     */
    protected $schema;

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
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return self
     */
    public function setRequired($required = null)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return Schema
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param Schema $schema
     *
     * @return self
     */
    public function setSchema($schema = null)
    {
        $this->schema = $schema;

        return $this;
    }
}
