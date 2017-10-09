<?php

namespace Joli\Jane\OpenApi\Tests\Expected\Model;

class ObjectProperty
{
    /**
     * @var string
     */
    protected $stringProperty;

    /**
     * @return string
     */
    public function getStringProperty()
    {
        return $this->stringProperty;
    }

    /**
     * @param string $stringProperty
     *
     * @return self
     */
    public function setStringProperty($stringProperty = null)
    {
        $this->stringProperty = $stringProperty;

        return $this;
    }
}
