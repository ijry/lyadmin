<?php

namespace Joli\Jane\OpenApi\Generator\Parameter;

use Doctrine\Common\Inflector\Inflector;
use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Runtime\Reference;
use Joli\Jane\Reference\Resolver;
use Joli\Jane\OpenApi\Model\BodyParameter;
use Joli\Jane\OpenApi\Model\Schema;
use PhpParser\Node;
use PhpParser\Parser;

class BodyParameterGenerator extends ParameterGenerator
{
    /**
     * @var Resolver
     */
    private $resolver;

    public function __construct(Parser $parser, Resolver $resolver)
    {
        parent::__construct($parser);

        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     *
     * @param $parameter BodyParameter
     */
    public function generateMethodParameter($parameter, Context $context)
    {
        $name = Inflector::camelize($parameter->getName());

        list($class, $array) = $this->getClass($parameter, $context);

        if (null === $array || true === $array) {
            if ($class == "array") {
                return new Node\Param($name, null, "array");
            }

            return new Node\Param($name);
        }

        return new Node\Param($name, null, $class);
    }

    /**
     * {@inheritDoc}
     *
     * @param $parameter BodyParameter
     */
    public function generateDocParameter($parameter, Context $context)
    {
        list($class, $array) = $this->getClass($parameter, $context);

        if (null === $class) {
            return sprintf('%s $%s %s', 'mixed', Inflector::camelize($parameter->getName()), $parameter->getDescription() ?: '');
        }

        return sprintf('%s $%s %s', $class, Inflector::camelize($parameter->getName()), $parameter->getDescription() ?: '');
    }

    /**
     * @param BodyParameter $parameter
     * @param Context $context
     *
     * @return array
     */
    protected function getClass(BodyParameter $parameter, Context $context)
    {
        $resolvedSchema = null;
        $array          = false;
        $schema         = $parameter->getSchema();

        if ($schema instanceof Reference) {
            $resolvedSchema = $this->resolver->resolve($schema);
        }

        if ($schema instanceof Schema && $schema->getType() == "array" && $schema->getItems() instanceof Reference) {
            $resolvedSchema = $this->resolver->resolve($schema->getItems());
            $array          = true;
        }

        if ($resolvedSchema === null) {
            return [$schema->getType(), null];
        }

        $class = $context->getObjectClassMap()[spl_object_hash($resolvedSchema)];
        $class = "\\" . $context->getNamespace() . "\\Model\\" . $class->getName();

        if ($array) {
            $class .= "[]";
        }

        return [$class, $array];
    }
}
