<?php

namespace Joli\Jane\Reference;

use Joli\Jane\Runtime\Reference;

class Replacer
{
    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @param Resolver $resolver A resolver for reference
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Replace all references for an object
     *
     * @param mixed $object Object to replace
     * @param mixed $root   Root object
     */
    public function replace($object, $root = null)
    {
        if ($root === null) {
            $root = $object;
        }

        $resolver = $this->resolver;
        $replacer = $this;
        $replace  = \Closure::bind(function () use ($resolver, $object, $replacer, $root) {
            foreach ($this as &$value) {
                if ($value instanceof Reference) {
                    $value = $resolver->resolve($value, $root);
                } elseif (is_object($value)) {
                    $replacer->replace($value, $root);
                }
            }
        }, $object, $object);

        $replace();
    }
}
