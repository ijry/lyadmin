<?php

namespace Joli\Jane\Guesser\Guess;

class Property
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var mixed
     */
    private $object;

    /**
     * @var bool
     */
    private $nullable;

    public function __construct($object, $name, $nullable = false, $type = null)
    {
        $this->name = $name;
        $this->object = $object;
        $this->nullable = $nullable;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Return name of the property.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Whether the property is nullable or not.
     *
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * Return type of the property.
     *
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type.
     *
     * @param Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }
}
