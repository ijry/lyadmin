<?php

namespace Joli\Jane\OpenApi\Generator\Parameter;

use Joli\Jane\Generator\Context\Context;
use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\Node\Expr;

abstract class ParameterGenerator
{
    /**
     * @var Parser
     */
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param $parameter
     * @param Context $context
     *
     * @return Node\Param|null
     */
    public function generateMethodParameter($parameter, Context $context)
    {
        return null;
    }

    /**
     * @param $parameter
     * @param Context $context
     *
     * @return string
     */
    public function generateDocParameter($parameter, Context $context)
    {
        return '';
    }

    /**
     * @param $parameter
     *
     * @return Node\Expr[]
     */
    public function generateQueryParamStatements($parameter, Expr $queryParamVariable)
    {
        return [];
    }
}
