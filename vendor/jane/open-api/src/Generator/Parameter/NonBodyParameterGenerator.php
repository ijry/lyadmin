<?php

namespace Joli\Jane\OpenApi\Generator\Parameter;

use Doctrine\Common\Inflector\Inflector;
use Joli\Jane\Generator\Context\Context;
use Joli\Jane\OpenApi\Model\FormDataParameterSubSchema;
use Joli\Jane\OpenApi\Model\HeaderParameterSubSchema;
use Joli\Jane\OpenApi\Model\PathParameterSubSchema;
use Joli\Jane\OpenApi\Model\QueryParameterSubSchema;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;

abstract class NonBodyParameterGenerator extends ParameterGenerator
{
    /**
     * {@inheritDoc}
     *
     * @param $parameter PathParameterSubSchema|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema
     */
    public function generateMethodParameter($parameter, Context $context)
    {
        $name            = Inflector::camelize($parameter->getName());
        $methodParameter = new Node\Param($name);

        if (!$parameter->getRequired() || $parameter->getDefault() !== null) {
            $methodParameter->default = $this->getDefaultAsExpr($parameter);
        }

        return $methodParameter;
    }

    /**
     * {@inheritDoc}
     *
     * @param $parameter PathParameterSubSchema|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema
     */
    public function generateQueryParamStatements($parameter, Expr $queryParamVariable)
    {
        $statements = [];

        if (!$parameter->getRequired() || $parameter->getDefault() !== null) {
            $statements[] = new Expr\MethodCall($queryParamVariable, 'setDefault', [
                new Node\Arg(new Scalar\String_($parameter->getName())),
                new Node\Arg($this->getDefaultAsExpr($parameter))
            ]);
        }

        if ($parameter->getRequired() && $parameter->getDefault() === null) {
            $statements[] = new Expr\MethodCall($queryParamVariable, 'setRequired', [new Node\Arg(new Scalar\String_($parameter->getName()))]);
        }

        return $statements;
    }

    /**
     * Generate a default value as an Expr
     *
     * @param $parameter PathParameterSubSchema|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema
     *
     * @return Expr
     */
    protected function getDefaultAsExpr($parameter)
    {
        return $this->parser->parse("<?php " . var_export($parameter->getDefault(), true) . ";")[0];
    }

    /**
     * {@inheritDoc}
     *
     * @param $parameter PathParameterSubSchema|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema
     */
    public function generateDocParameter($parameter, Context $context)
    {
        return sprintf('%s $%s %s', $this->convertParameterType($parameter->getType()), Inflector::camelize($parameter->getName()), $parameter->getDescription() ?: '');
    }

    /**
     * @param $parameter PathParameterSubSchema|HeaderParameterSubSchema|FormDataParameterSubSchema|QueryParameterSubSchema
     *
     * @return string
     */
    public function generateQueryDocParameter($parameter)
    {
        return sprintf('@var %s $%s %s', $this->convertParameterType($parameter->getType()), $parameter->getName(), $parameter->getDescription() ?: '');
    }

    public function convertParameterType($type)
    {
        $convertArray = [
            "string" => "string",
            "number" => "float",
            "boolean" => "bool",
            "integer" => "int",
            "array" => "array"
        ];

        return $convertArray[$type];
    }
}
