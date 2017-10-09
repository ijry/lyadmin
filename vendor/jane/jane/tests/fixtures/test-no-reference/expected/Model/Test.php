<?php

namespace Joli\Jane\Tests\Expected\Model;

class Test
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     *
     * @return self
     */
    public function setString($string = null)
    {
        $this->string = $string;

        return $this;
    }
}
