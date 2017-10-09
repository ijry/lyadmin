<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

class ArrayType extends Type
{
    protected $itemType;

    public function __construct($object, Type $itemType, $type = 'array')
    {
        parent::__construct($object, $type);

        $this->itemType = $itemType;
    }

    public function __toString()
    {
        if ($this->itemType instanceof MultipleType) {
            $typesString = [];

            foreach ($this->itemType->getTypes() as $type) {
                $typesString[] = $type->__toString().'[]';
            }

            return implode('|', $typesString);
        }

        return $this->itemType->__toString().'[]';
    }

    /**
     * (@inheritDoc}
     */
    public function createDenormalizationStatement(Context $context, Expr $input)
    {
        $valuesVar     = new Expr\Variable($context->getUniqueVariableName('values'));
        $statements    = [
            // $values = [];
            new Expr\Assign($valuesVar, $this->createArrayValueStatement()),
        ];

        $loopValueVar   = new Expr\Variable($context->getUniqueVariableName('value'));
        $loopKeyVar     = $this->createLoopKeyStatement($context);

        list($subStatements, $outputExpr) = $this->itemType->createDenormalizationStatement($context, $loopValueVar);

        $loopStatements   = array_merge($subStatements, [
            new Expr\Assign($this->createLoopOutputAssignement($valuesVar, $loopKeyVar), $outputExpr)
        ]);

        $statements[]     = new Stmt\Foreach_($input, $loopValueVar, [
            'keyVar' => $loopKeyVar,
            'stmts'  => $loopStatements
        ]);

        return [$statements, $valuesVar];
    }

    /**
     * (@inheritDoc}
     */
    public function createNormalizationStatement(Context $context, Expr $input)
    {
        $valuesVar     = new Expr\Variable($context->getUniqueVariableName('values'));
        $statements    = [
            // $values = [];
            new Expr\Assign($valuesVar, $this->createNormalizationArrayValueStatement()),
        ];

        $loopValueVar   = new Expr\Variable($context->getUniqueVariableName('value'));
        $loopKeyVar     = $this->createLoopKeyStatement($context);

        list($subStatements, $outputExpr) = $this->itemType->createNormalizationStatement($context, $loopValueVar);

        $loopStatements   = array_merge($subStatements, [
            new Expr\Assign($this->createNormalizationLoopOutputAssignement($valuesVar, $loopKeyVar), $outputExpr)
        ]);

        $statements[]     = new Stmt\Foreach_($input, $loopValueVar, [
            'keyVar' => $loopKeyVar,
            'stmts'  => $loopStatements
        ]);

        return [$statements, $valuesVar];
    }

    /**
     * (@inheritDoc}
     */
    public function getTypeHint()
    {
        return 'array';
    }

    protected function createArrayValueStatement()
    {
        return new Expr\Array_();
    }

    protected function createNormalizationArrayValueStatement()
    {
        return new Expr\Array_();
    }

    protected function createLoopKeyStatement(Context $context)
    {
        return null;
    }

    protected function createLoopOutputAssignement(Expr $valuesVar, $loopKeyVar)
    {
        return new Expr\ArrayDimFetch($valuesVar);
    }

    protected function createNormalizationLoopOutputAssignement(Expr $valuesVar, $loopKeyVar)
    {
        return new Expr\ArrayDimFetch($valuesVar);
    }
}
