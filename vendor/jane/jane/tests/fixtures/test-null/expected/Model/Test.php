<?php

namespace Joli\Jane\Tests\Expected\Model;

class Test
{
    /**
     * @var null
     */
    protected $onlyNull;
    /**
     * @var string|null
     */
    protected $nullOrString;


    public function getOnlyNull()
    {
        return $this->onlyNull;
    }

    /**
     * @param null $onlyNull
     *
     * @return self
     */
    public function setOnlyNull($onlyNull = null)
    {
        $this->onlyNull = $onlyNull;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNullOrString()
    {
        return $this->nullOrString;
    }

    /**
     * @param string|null $nullOrString
     *
     * @return self
     */
    public function setNullOrString($nullOrString = null)
    {
        $this->nullOrString = $nullOrString;

        return $this;
    }
}
