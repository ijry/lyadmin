<?php

namespace Joli\Jane\Guesser\Guess;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt;

class ClassGuess
{
    /**
     * @var string Name of the class
     */
    private $name;

    /**
     * @var array Options for generation
     */
    private $options;

    /**
     * @var mixed Object link to the generation
     */
    private $object;

    /**
     * @var Property[]
     */
    private $properties;

    public function __construct($object, $name, $options = [])
    {
        $this->name = $name;
        $this->object = $object;
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param Property[] $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }
}
 