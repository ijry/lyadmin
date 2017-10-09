<?php

namespace Joli\Jane\Generator;

use Doctrine\Common\Inflector\Inflector;

/**
 * Helper to generate name for property / class / ....
 */
class Naming
{
    /**
     * Get a property name
     *
     * @param $name
     * @return string
     */
    public function getPropertyName($name)
    {
        $name = $this->replaceDollar($name);

        return Inflector::camelize($name);
    }

    /**
     * Get a method name given a prefix
     *
     * @param $prefix
     * @param $name
     * @return string
     */
    public function getPrefixedMethodName($prefix, $name)
    {
        $name = $this->replaceDollar($name);

        return sprintf("%s%s", $prefix, Inflector::classify($name));
    }

    /**
     * Get a class name
     *
     * @param $name
     * @return string
     */
    public function getClassName($name)
    {
        $name = $this->replaceDollar($name);

        return Inflector::classify($name);
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function replaceDollar($name)
    {
        if (preg_match('/\$/', $name)) {
            $name = preg_replace_callback('/\$([a-z])/', function ($matches) {
                return 'dollar'.ucfirst($matches[1]);
            }, $name);
        }

        return $name;
    }
} 
