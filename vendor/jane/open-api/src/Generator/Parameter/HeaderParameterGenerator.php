<?php

namespace Joli\Jane\OpenApi\Generator\Parameter;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;

class HeaderParameterGenerator extends NonBodyParameterGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateQueryParamStatements($parameter, Expr $queryParamVariable)
    {
        $statements = parent::generateQueryParamStatements($parameter, $queryParamVariable);
        $statements[] = new Expr\MethodCall($queryParamVariable, 'setHeaderParameters', [
            new Node\Arg(new Expr\Array_([new Expr\ArrayItem(new Scalar\String_($parameter->getName()))])),
        ]);

        return $statements;
    }
}
