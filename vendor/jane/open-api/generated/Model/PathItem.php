<?php

namespace Joli\Jane\OpenApi\Model;

class PathItem
{
    /**
     * @var string
     */
    protected $dollarRef;
    /**
     * @var Operation
     */
    protected $get;
    /**
     * @var Operation
     */
    protected $put;
    /**
     * @var Operation
     */
    protected $post;
    /**
     * @var Operation
     */
    protected $delete;
    /**
     * @var Operation
     */
    protected $options;
    /**
     * @var Operation
     */
    protected $head;
    /**
     * @var Operation
     */
    protected $patch;
    /**
     * @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]
     */
    protected $parameters;

    /**
     * @return string
     */
    public function getDollarRef()
    {
        return $this->dollarRef;
    }

    /**
     * @param string $dollarRef
     *
     * @return self
     */
    public function setDollarRef($dollarRef = null)
    {
        $this->dollarRef = $dollarRef;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @param Operation $get
     *
     * @return self
     */
    public function setGet(Operation $get = null)
    {
        $this->get = $get;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getPut()
    {
        return $this->put;
    }

    /**
     * @param Operation $put
     *
     * @return self
     */
    public function setPut(Operation $put = null)
    {
        $this->put = $put;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Operation $post
     *
     * @return self
     */
    public function setPost(Operation $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param Operation $delete
     *
     * @return self
     */
    public function setDelete(Operation $delete = null)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Operation $options
     *
     * @return self
     */
    public function setOptions(Operation $options = null)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * @param Operation $head
     *
     * @return self
     */
    public function setHead(Operation $head = null)
    {
        $this->head = $head;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * @param Operation $patch
     *
     * @return self
     */
    public function setPatch(Operation $patch = null)
    {
        $this->patch = $patch;

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
}
