<?php

namespace Joli\Jane\OpenApi\Model;

class Schema
{
    /**
     * @var string
     */
    protected $dollarRef;
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
     * @var float
     */
    protected $multipleOf;
    /**
     * @var float
     */
    protected $maximum;
    /**
     * @var bool
     */
    protected $exclusiveMaximum;
    /**
     * @var float
     */
    protected $minimum;
    /**
     * @var bool
     */
    protected $exclusiveMinimum;
    /**
     * @var int
     */
    protected $maxLength;
    /**
     * @var int
     */
    protected $minLength;
    /**
     * @var string
     */
    protected $pattern;
    /**
     * @var int
     */
    protected $maxItems;
    /**
     * @var int
     */
    protected $minItems;
    /**
     * @var bool
     */
    protected $uniqueItems;
    /**
     * @var int
     */
    protected $maxProperties;
    /**
     * @var int
     */
    protected $minProperties;
    /**
     * @var string[]
     */
    protected $required;
    /**
     * @var mixed[]
     */
    protected $enum;
    /**
     * @var Schema|bool
     */
    protected $additionalProperties;
    /**
     * @var mixed|mixed[]
     */
    protected $type;
    /**
     * @var Schema|Schema[]
     */
    protected $items;
    /**
     * @var Schema[]
     */
    protected $allOf;
    /**
     * @var Schema[]
     */
    protected $properties;
    /**
     * @var string
     */
    protected $discriminator;
    /**
     * @var bool
     */
    protected $readOnly;
    /**
     * @var Xml
     */
    protected $xml;
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
     * @return float
     */
    public function getMultipleOf()
    {
        return $this->multipleOf;
    }

    /**
     * @param float $multipleOf
     *
     * @return self
     */
    public function setMultipleOf($multipleOf = null)
    {
        $this->multipleOf = $multipleOf;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @param float $maximum
     *
     * @return self
     */
    public function setMaximum($maximum = null)
    {
        $this->maximum = $maximum;

        return $this;
    }

    /**
     * @return bool
     */
    public function getExclusiveMaximum()
    {
        return $this->exclusiveMaximum;
    }

    /**
     * @param bool $exclusiveMaximum
     *
     * @return self
     */
    public function setExclusiveMaximum($exclusiveMaximum = null)
    {
        $this->exclusiveMaximum = $exclusiveMaximum;

        return $this;
    }

    /**
     * @return float
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @param float $minimum
     *
     * @return self
     */
    public function setMinimum($minimum = null)
    {
        $this->minimum = $minimum;

        return $this;
    }

    /**
     * @return bool
     */
    public function getExclusiveMinimum()
    {
        return $this->exclusiveMinimum;
    }

    /**
     * @param bool $exclusiveMinimum
     *
     * @return self
     */
    public function setExclusiveMinimum($exclusiveMinimum = null)
    {
        $this->exclusiveMinimum = $exclusiveMinimum;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     *
     * @return self
     */
    public function setMaxLength($maxLength = null)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->minLength;
    }

    /**
     * @param int $minLength
     *
     * @return self
     */
    public function setMinLength($minLength = null)
    {
        $this->minLength = $minLength;

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return self
     */
    public function setPattern($pattern = null)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxItems()
    {
        return $this->maxItems;
    }

    /**
     * @param int $maxItems
     *
     * @return self
     */
    public function setMaxItems($maxItems = null)
    {
        $this->maxItems = $maxItems;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinItems()
    {
        return $this->minItems;
    }

    /**
     * @param int $minItems
     *
     * @return self
     */
    public function setMinItems($minItems = null)
    {
        $this->minItems = $minItems;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUniqueItems()
    {
        return $this->uniqueItems;
    }

    /**
     * @param bool $uniqueItems
     *
     * @return self
     */
    public function setUniqueItems($uniqueItems = null)
    {
        $this->uniqueItems = $uniqueItems;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxProperties()
    {
        return $this->maxProperties;
    }

    /**
     * @param int $maxProperties
     *
     * @return self
     */
    public function setMaxProperties($maxProperties = null)
    {
        $this->maxProperties = $maxProperties;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinProperties()
    {
        return $this->minProperties;
    }

    /**
     * @param int $minProperties
     *
     * @return self
     */
    public function setMinProperties($minProperties = null)
    {
        $this->minProperties = $minProperties;

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
     * @return mixed[]
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * @param mixed[] $enum
     *
     * @return self
     */
    public function setEnum(array $enum = null)
    {
        $this->enum = $enum;

        return $this;
    }

    /**
     * @return Schema|bool
     */
    public function getAdditionalProperties()
    {
        return $this->additionalProperties;
    }

    /**
     * @param Schema|bool $additionalProperties
     *
     * @return self
     */
    public function setAdditionalProperties($additionalProperties = null)
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    /**
     * @return mixed|mixed[]
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed|mixed[] $type
     *
     * @return self
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Schema|Schema[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Schema|Schema[] $items
     *
     * @return self
     */
    public function setItems($items = null)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Schema[]
     */
    public function getAllOf()
    {
        return $this->allOf;
    }

    /**
     * @param Schema[] $allOf
     *
     * @return self
     */
    public function setAllOf(array $allOf = null)
    {
        $this->allOf = $allOf;

        return $this;
    }

    /**
     * @return Schema[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param Schema[] $properties
     *
     * @return self
     */
    public function setProperties(\ArrayObject $properties = null)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscriminator()
    {
        return $this->discriminator;
    }

    /**
     * @param string $discriminator
     *
     * @return self
     */
    public function setDiscriminator($discriminator = null)
    {
        $this->discriminator = $discriminator;

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
     * @return Xml
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @param Xml $xml
     *
     * @return self
     */
    public function setXml(Xml $xml = null)
    {
        $this->xml = $xml;

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
