<?php

namespace Joli\Jane\OpenApi\Model;

class Operation
{
    /**
     * @var string[]
     */
    protected $tags;
    /**
     * @var string
     */
    protected $summary;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var ExternalDocs
     */
    protected $externalDocs;
    /**
     * @var string
     */
    protected $operationId;
    /**
     * @var string[]
     */
    protected $produces;
    /**
     * @var string[]
     */
    protected $consumes;
    /**
     * @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]
     */
    protected $parameters;
    /**
     * @var Response|JsonReference[]|mixed[]
     */
    protected $responses;
    /**
     * @var string[]
     */
    protected $schemes;
    /**
     * @var bool
     */
    protected $deprecated;
    /**
     * @var string[][][]
     */
    protected $security;

    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     *
     * @return self
     */
    public function setTags(array $tags = null)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     *
     * @return self
     */
    public function setSummary($summary = null)
    {
        $this->summary = $summary;

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

    /**
     * @return string
     */
    public function getOperationId()
    {
        return $this->operationId;
    }

    /**
     * @param string $operationId
     *
     * @return self
     */
    public function setOperationId($operationId = null)
    {
        $this->operationId = $operationId;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getProduces()
    {
        return $this->produces;
    }

    /**
     * @param string[] $produces
     *
     * @return self
     */
    public function setProduces(array $produces = null)
    {
        $this->produces = $produces;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getConsumes()
    {
        return $this->consumes;
    }

    /**
     * @param string[] $consumes
     *
     * @return self
     */
    public function setConsumes(array $consumes = null)
    {
        $this->consumes = $consumes;

        return $this;
    }

    /**
     * @return BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[] $parameters
     *
     * @return self
     */
    public function setParameters(array $parameters = null)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return Response|JsonReference[]|mixed[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param Response|JsonReference[]|mixed[] $responses
     *
     * @return self
     */
    public function setResponses($responses = null)
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getSchemes()
    {
        return $this->schemes;
    }

    /**
     * @param string[] $schemes
     *
     * @return self
     */
    public function setSchemes(array $schemes = null)
    {
        $this->schemes = $schemes;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated
     *
     * @return self
     */
    public function setDeprecated($deprecated = null)
    {
        $this->deprecated = $deprecated;

        return $this;
    }

    /**
     * @return string[][][]
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * @param string[][][] $security
     *
     * @return self
     */
    public function setSecurity(array $security = null)
    {
        $this->security = $security;

        return $this;
    }
}
