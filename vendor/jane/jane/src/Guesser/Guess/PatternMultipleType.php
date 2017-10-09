<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

class PatternMultipleType extends Type
{
    protected $types = array();

    public function __construct($object, array $types = array())
    {
        parent::__construct($object, 'mixed');

        $this->types = $types;
    }

    /**
     * Add a type
     *
     * @param string $pattern
     * @param Type   $type
     *
     * @return $this
     */
    public function addType($pattern, Type $type)
    {
        $this->types[$pattern] = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $stringTypes = array_map(function ($type) {
            return $type->__toString().'[]';
        }, $this->types);

        return implode('|', $stringTypes);
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
        $loopStatements = [];

        foreach ($this->types as $pattern => $type) {
            list($typeStatements, $typeOutput) = $type->createDenormalizationStatement($context, $loopValueVar);
            $loopStatements                    = array_merge($loopStatements, [
                new Stmt\If_(
                    new Expr\BinaryOp\BooleanAnd(
                        new Expr\FuncCall(new Name('preg_match'), [
                            new Arg(new Expr\ConstFetch(new Name("'/".str_replace('/', '\/', $pattern)."/'"))),
                            new Arg($loopKeyVar)
                        ]),
                        $type->createConditionStatement($loopValueVar)
                    ),
                    [
                        'stmts' => array_merge($typeStatements, [
                            new Expr\Assign(new Expr\ArrayDimFetch($valuesVar, $loopKeyVar), $typeOutput),
                            new Stmt\Continue_()
                        ])
                    ]
                )
            ]);
        }

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
        $loopStatements = [];

        foreach ($this->types as $pattern => $type) {
            list($typeStatements, $typeOutput) = $type->createNormalizationStatement($context, $loopValueVar);
            $loopStatements                    = array_merge($loopStatements, [
                new Stmt\If_(
                    new Expr\BinaryOp\BooleanAnd(
                        new Expr\FuncCall(new Name('preg_match'), [
                            new Arg(new Expr\ConstFetch(new Name("'/".str_replace('/', '\/', $pattern)."/'"))),
                            new Arg($loopKeyVar)
                        ]),
                        $type->createNormalizationConditionStatement($loopValueVar)
                    ),
                    [
                        'stmts' => array_merge($typeStatements, [
                            new Expr\Assign(new Expr\PropertyFetch($valuesVar, $loopKeyVar), $typeOutput),
                            new Stmt\Continue_()
                        ])
                    ]
                )
            ]);
        }

        $statements[]     = new Stmt\Foreach_($input, $loopValueVar, [
            'keyVar' => $loopKeyVar,
            'stmts'  => $loopStatements
        ]);

        return [$statements, $valuesVar];
    }

    /**
     * {@inheritDoc}
     */
    protected function createArrayValueStatement()
    {
        return new Expr\New_(new Name('\ArrayObject'), [
            new Expr\Array_(),
            new Expr\ClassConstFetch(new Name('\ArrayObject'), 'ARRAY_AS_PROPS')
        ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function createNormalizationArrayValueStatement()
    {
        return new Expr\New_(new Name('\stdClass'));
    }

    /**
     * {@inheritDoc}
     */
    protected function createLoopKeyStatement(Context $context)
    {
        return new Expr\Variable($context->getUniqueVariableName('key'));
    }
}
 