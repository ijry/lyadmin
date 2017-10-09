<?php

namespace Joli\Jane\OpenApi\Model;

class Header
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $format;
    /**
     * @var PrimitivesItems
     */
    protected $items;
    /**
     * @var string
     */
    protected $collectionFormat;
    /**
     * @var mixed
     */
    protected $default;
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
     * @var mixed[]
     */
    protected $enum;
    /**
     * @var float
     */
    protected $multipleOf;
    /**
     * @var string
     */
    protected $description;

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
     * @return PrimitivesItems
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param PrimitivesItems $items
     *
     * @return self
     */
    public function setItems(PrimitivesItems $items = null)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return string
     */
    public function getCollectionFormat()
    {
        return $this->collectionFormat;
    }

    /**
     * @param string $collectionFormat
     *
     * @return self
     */
    public function setCollectionFormat($collectionFormat = null)
    {
        $this->collectionFormat = $collectionFormat;

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
}
