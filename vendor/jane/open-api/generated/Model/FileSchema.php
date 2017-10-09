<?php

namespace Joli\Jane\OpenApi\Model;

class FileSchema
{
    /**
     * @var string
     */
    protected $format;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var mixed
     */
    protected $default;
    /**
     * @var string[]
     */
    protected $required;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var bool
     */
    protected $readOnly;
    /**
     * @var ExternalDocs
     */
    protected $externalDocs;
    /**
     * @var mixed
     */
    protected $example;

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return self
     */
    public function setFormat($format = null)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title = null)
    {
        $this->title = $title;

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

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return self
     */
    public function setDefault($default = null)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param string[] $required
     *
     * @return self
     */
    public function setRequired(array $required = null)
    {
        $this->required = $required;

        return $this;
    }

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
     * @return bool
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     *
     * @return self
     */
    public function setReadOnly($readOnly = null)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @return ExternalDocs
     */
    public function getExternalDocs()
    {
        return $this->externalDocs;
    }

    /**
     * @param ExternalDocs $externalDocs
     *
     * @return self
     */
    public function setExternalDocs(ExternalDocs $externalDocs = null)
    {
        $this->externalDocs = $externalDocs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * @param mixed $example
     *
     * @return self
     */
    public function setExample($example = null)
    {
        $this->example = $example;

        return $this;
    }
}
