<?php

namespace Joli\Jane\OpenApi\Model;

class OpenApi
{
    /**
     * @var string
     */
    protected $swagger;
    /**
     * @var Info
     */
    protected $info;
    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var string[]
     */
    protected $schemes;
    /**
     * @var string[]
     */
    protected $consumes;
    /**
     * @var string[]
     */
    protected $produces;
    /**
     * @var mixed[]|PathItem[]
     */
    protected $paths;
    /**
     * @var Schema[]
     */
    protected $definitions;
    /**
     * @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]
     */
    protected $parameters;
    /**
     * @var Response[]
     */
    protected $responses;
    /**
     * @var string[][][]
     */
    protected $security;
    /**
     * @var BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[]
     */
    protected $securityDefinitions;
    /**
     * @var Tag[]
     */
    protected $tags;
    /**
     * @var ExternalDocs
     */
    protected $externalDocs;

    /**
     * @return string
     */
    public function getSwagger()
    {
        return $this->swagger;
    }

    /**
     * @param string $swagger
     *
     * @return self
     */
    public function setSwagger($swagger = null)
    {
        $this->swagger = $swagger;

        return $this;
    }

    /**
     * @return Info
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param Info $info
     *
     * @return self
     */
    public function setInfo(Info $info = null)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return self
     */
    public function setHost($host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     *
     * @return self
     */
    public function setBasePath($basePath = null)
    {
        $this->basePath = $basePath;

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
     * @return mixed[]|PathItem[]
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param mixed[]|PathItem[] $paths
     *
     * @return self
     */
    public function setPaths($paths = null)
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * @return Schema[]
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * @param Schema[] $definitions
     *
     * @return self
     */
    public function setDefinitions(\ArrayObject $definitions = null)
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * @return BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[] $parameters
     *
     * @return self
     */
    public function setParameters(\ArrayObject $parameters = null)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return Response[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param Response[] $responses
     *
     * @return self
     */
    public function setResponses(\ArrayObject $responses = null)
    {
        $this->responses = $responses;

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

    /**
     * @return BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[]
     */
    public function getSecurityDefinitions()
    {
        return $this->securityDefinitions;
    }

    /**
     * @param BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[] $securityDefinitions
     *
     * @return self
     */
    public function setSecurityDefinitions(\ArrayObject $securityDefinitions = null)
    {
        $this->securityDefinitions = $securityDefinitions;

        return $this;
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $tags
     *
     * @return self
     */
    public function setTags(array $tags = null)
    {
        $this->tags = $tags;

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
