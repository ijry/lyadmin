<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;

use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;

class ObjectType extends Type
{
    private $className;

    private $discriminants;

    public function __construct($object, $className, $discriminants = array())
    {
        parent::__construct($object, 'object');

        $this->className = $className;
        $this->discriminants = $discriminants;
    }

    public function __toString()
    {
        return $this->className;
    }

    /**
     * (@inheritDoc}
     */
    protected function createDenormalizationValueStatement(Context $context, Expr $input)
    {
        $fqdn = $context->getNamespace() . '\\Model\\'. $this->className;

        return new Expr\MethodCall(new Expr\PropertyFetch(new Expr\Variable('this'), 'serializer'), 'deserialize', [
            new Arg($input),
            new Arg(new Scalar\String_($fqdn)),
            new Arg(new Scalar\String_('raw')),
            new Arg(new Expr\Variable('context'))
        ]);
    }

    /**
     * (@inheritDoc}
     */
    protected function createNormalizationValueStatement(Context $context, Expr $input)
    {
        return new Expr\MethodCall(new Expr\PropertyFetch(new Expr\Variable('this'), 'serializer'), 'serialize', [
            new Arg($input),
            new Arg(new Scalar\String_('raw')),
            new Arg(new Expr\Variable('context'))
        ]);
    }

    /**
     * (@inheritDoc}
     */
    public function createConditionStatement(Expr $input)
    {
        $conditionStatement = parent::createConditionStatement($input);

        foreach ($this->discriminants as $key => $values) {
            $issetCondition = new Expr\FuncCall(
                new Name('isset'),
                [
                    new Arg(new Expr\PropertyFetch($input, sprintf("{'%s'}", $key)))
                ]
            );

            $logicalOr = null;

            if ($values !== null) {
                foreach ($values as $value) {
                    if ($logicalOr === null) {
                        $logicalOr = new Expr\BinaryOp\Equal(
                            new Expr\PropertyFetch($input, sprintf("{'%s'}", $key)),
                            new Scalar\String_($value)
                        );
                    } else {
                        $logicalOr = new Expr\BinaryOp\LogicalOr(
                            $logicalOr,
                            new Expr\BinaryOp\Equal(
                                new Expr\PropertyFetch($input, sprintf("{'%s'}", $key)),
                                new Scalar\String_($value)
                            )
                        );
                    }
                }
            }

            if ($logicalOr !== null) {
                $conditionStatement = new Expr\BinaryOp\LogicalAnd($conditionStatement, new Expr\BinaryOp\LogicalAnd($issetCondition, $logicalOr));
            } else {
                $conditionStatement = new Expr\BinaryOp\LogicalAnd($conditionStatement, $issetCondition);
            }
        }

        return $conditionStatement;
    }

    /**
     * (@inheritDoc}
     */
    public function getTypeHint()
    {
        return $this->className;
    }
}
