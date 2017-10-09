<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

class MultipleType extends Type
{
    protected $types;

    public function __construct($object, array $types = array())
    {
        parent::__construct($object, 'mixed');

        $this->types = $types;
    }

    /**
     * Add a type
     *
     * @param Type $type
     *
     * @return $this
     */
    public function addType(Type $type)
    {
        if ($type instanceof MultipleType) {
            foreach ($type->getTypes() as $subType) {
                $this->types[] = $subType;
            }

            return $this;
        }

        $this->types[] = $type;

        return $this;
    }

    /**
     * Return a list of types
     *
     * @return Type[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $stringTypes = array_map(function ($type) {
            return $type->__toString();
        }, $this->types);

        return implode('|', $stringTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeHint()
    {
        // We have exactly two types: one null and an object
        if (count($this->types) === 2) {
            list($type1, $type2) = $this->types;

            if ($this->isOptionalObjectType($type1, $type2)) {
                return $type2->getTypeHint();
            }

            if ($this->isOptionalObjectType($type2, $type1)) {
                return $type1->getTypeHint();
            }
        }

        return null;
    }

    private function isOptionalObjectType(Type $nullType, Type $objectType)
    {
        return 'null' === $nullType->getName() && $objectType instanceof ObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function createDenormalizationStatement(Context $context, Expr $input)
    {
        $output     = new Expr\Variable($context->getUniqueVariableName('value'));
        $statements = [
            new Expr\Assign($output, $input)
        ];

        foreach ($this->getTypes() as $type) {
            list ($typeStatements, $typeOutput) = $type->createDenormalizationStatement($context, $input);

            $statements[] = new Stmt\If_(
                $type->createConditionStatement($input),
                [
                    'stmts' => array_merge(
                        $typeStatements, [
                            new Expr\Assign($output, $typeOutput)
                        ]
                    )
                ]
            );
        }

        return [$statements, $output];
    }
    /**
     * {@inheritdoc}
     */
    public function createNormalizationStatement(Context $context, Expr $input)
    {
        $output     = new Expr\Variable($context->getUniqueVariableName('value'));
        $statements = [
            new Expr\Assign($output, $input)
        ];

        foreach ($this->getTypes() as $type) {
            list ($typeStatements, $typeOutput) = $type->createNormalizationStatement($context, $input);

            $statements[] = new Stmt\If_(
                $type->createNormalizationConditionStatement($input),
                [
                    'stmts' => array_merge(
                        $typeStatements, [
                            new Expr\Assign($output, $typeOutput)
                        ]
                    )
                ]
            );
        }

        return [$statements, $output];
    }
}
