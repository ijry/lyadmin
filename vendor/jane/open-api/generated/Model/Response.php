<?php

namespace Joli\Jane\OpenApi\Model;

class Response
{
    /**
     * @var string
     */
    protected $description;
    /**
     * @var Schema|FileSchema
     */
    protected $schema;
    /**
     * @var Header[]
     */
    protected $headers;
    /**
     * @var mixed[]
     */
    protected $examples;

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
     * @return Schema|FileSchema
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param Schema|FileSchema $schema
     *
     * @return self
     */
    public function setSchema($schema = null)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return Header[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param Header[] $headers
     *
     * @return self
     */
    public function setHeaders(\ArrayObject $headers = null)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getExamples()
    {
        return $this->examples;
    }

    /**
     * @param mixed[] $examples
     *
     * @return self
     */
    public function setExamples(\ArrayObject $examples = null)
    {
        $this->examples = $examples;

        return $this;
    }
}
