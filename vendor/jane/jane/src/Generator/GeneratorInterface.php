<?php

namespace Joli\Jane\Generator;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Model\JsonSchema;

interface GeneratorInterface
{
    /**
     * Generate a set of files given an object and insert it into the context
     *
     * @param mixed   $object     Object to generate from
     * @param string  $className  Class to generate
     * @param Context $context    Context for generation
     *
     * @return void
     */
    public function generate($object, $className, Context $context);
}
