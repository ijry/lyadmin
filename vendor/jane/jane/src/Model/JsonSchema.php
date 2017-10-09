<?php

namespace Joli\Jane\Model;

class JsonSchema
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $dollarSchema;
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
     * @var bool|JsonSchema
     */
    protected $additionalItems;
    /**
     * @var JsonSchema|JsonSchema[]
     */
    protected $items;
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
     * @var bool|JsonSchema
     */
    protected $additionalProperties;
    /**
     * @var JsonSchema[]
     */
    protected $definitions;
    /**
     * @var JsonSchema[]
     */
    protected $properties;
    /**
     * @var JsonSchema[]
     */
    protected $patternProperties;
    /**
     * @var JsonSchema[]|string[][]
     */
    protected $dependencies;
    /**
     * @var mixed[]
     */
    protected $enum;
    /**
     * @var mixed|mixed[]
     */
    protected $type;
    /**
     * @var string
     */
    protected $format;
    /**
     * @var JsonSchema[]
     */
    protected $allOf;
    /**
     * @var JsonSchema[]
     */
    protected $anyOf;
    /**
     * @var JsonSchema[]
     */
    protected $oneOf;
    /**
     * @var JsonSchema
     */
    protected $not;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getDollarSchema()
    {
        return $this->dollarSchema;
    }

    /**
     * @param string $dollarSchema
     *
     * @return self
     */
    public function setDollarSchema($dollarSchema = null)
    {
        $this->dollarSchema = $dollarSchema;

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
     * @return bool|JsonSchema
     */
    public function getAdditionalItems()
    {
        return $this->additionalItems;
    }

    /**
     * @param bool|JsonSchema $additionalItems
     *
     * @return self
     */
    public function setAdditionalItems($additionalItems = null)
    {
        $this->additionalItems = $additionalItems;

        return $this;
    }

    /**
     * @return JsonSchema|JsonSchema[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param JsonSchema|JsonSchema[] $items
     *
     * @return self
     */
    public function setItems($items = null)
    {
        $this->items = $items;

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
     * @return bool|JsonSchema
     */
    public function getAdditionalProperties()
    {
        return $this->additionalProperties;
    }

    /**
     * @param bool|JsonSchema $additionalProperties
     *
     * @return self
     */
    public function setAdditionalProperties($additionalProperties = null)
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    /**
     * @return JsonSchema[]
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * @param JsonSchema[] $definitions
     *
     * @return self
     */
    public function setDefinitions(\ArrayObject $definitions = null)
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * @return JsonSchema[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param JsonSchema[] $properties
     *
     * @return self
     */
    public function setProperties(\ArrayObject $properties = null)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return JsonSchema[]
     */
    public function getPatternProperties()
    {
        return $this->patternProperties;
    }

    /**
     * @param JsonSchema[] $patternProperties
     *
     * @return self
     */
    public function setPatternProperties(\ArrayObject $patternProperties = null)
    {
        $this->patternProperties = $patternProperties;

        return $this;
    }

    /**
     * @return JsonSchema[]|string[][]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @param JsonSchema[]|string[][] $dependencies
     *
     * @return self
     */
    public function setDependencies(\ArrayObject $dependencies = null)
    {
        $this->dependencies = $dependencies;

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
     * @return JsonSchema[]
     */
    public function getAllOf()
    {
        return $this->allOf;
    }

    /**
     * @param JsonSchema[] $allOf
     *
     * @return self
     */
    public function setAllOf(array $allOf = null)
    {
        $this->allOf = $allOf;

        return $this;
    }

    /**
     * @return JsonSchema[]
     */
    public function getAnyOf()
    {
        return $this->anyOf;
    }

    /**
     * @param JsonSchema[] $anyOf
     *
     * @return self
     */
    public function setAnyOf(array $anyOf = null)
    {
        $this->anyOf = $anyOf;

        return $this;
    }

    /**
     * @return JsonSchema[]
     */
    public function getOneOf()
    {
        return $this->oneOf;
    }

    /**
     * @param JsonSchema[] $oneOf
     *
     * @return self
     */
    public function setOneOf(array $oneOf = null)
    {
        $this->oneOf = $oneOf;

        return $this;
    }

    /**
     * @return JsonSchema
     */
    public function getNot()
    {
        return $this->not;
    }

    /**
     * @param JsonSchema $not
     *
     * @return self
     */
    public function setNot(JsonSchema $not = null)
    {
        $this->not = $not;

        return $this;
    }
}
