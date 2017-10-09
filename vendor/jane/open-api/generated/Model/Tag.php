<?php

namespace Joli\Jane\OpenApi\Model;

class Tag
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var ExternalDocs
     */
    protected $externalDocs;

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
}
