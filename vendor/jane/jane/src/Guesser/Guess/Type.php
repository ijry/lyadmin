<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;

use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

class Type
{
    const TYPE_BOOLEAN = 'bool';
    const TYPE_INTEGER = 'int';
    const TYPE_FLOAT   = 'float';
    const TYPE_STRING  = 'string';
    const TYPE_NULL    = 'null';
    const TYPE_MIXED   = 'mixed';
    const TYPE_ARRAY   = 'array';
    const TYPE_OBJECT  = 'object';

    protected $conditionMapping = [
        self::TYPE_BOOLEAN => 'is_bool',
        self::TYPE_INTEGER => 'is_int',
        self::TYPE_FLOAT   => 'is_float',
        self::TYPE_STRING  => 'is_string',
        self::TYPE_NULL    => 'is_null',
        self::TYPE_MIXED   => 'isset',
        self::TYPE_ARRAY   => 'is_array',
        self::TYPE_OBJECT  => 'is_object',
    ];

    protected $normalizationConditionMapping = [
        self::TYPE_BOOLEAN => 'is_bool',
        self::TYPE_INTEGER => 'is_int',
        self::TYPE_FLOAT   => 'is_float',
        self::TYPE_STRING  => 'is_string',
        self::TYPE_NULL    => 'is_null',
        self::TYPE_MIXED   => '!is_null',
        self::TYPE_ARRAY   => 'is_array',
        self::TYPE_OBJECT  => 'is_object',
    ];

    protected $name;

    protected $object;

    public function __construct($object, $name)
    {
        $this->name   = $name;
        $this->object = null;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Return the denormalization used for this type
     *
     * @param Context  $context
     * @param Expr     $input
     *
     * @return Expr[], Expr First array contain all the expr need to transform the input value, second statement is the expr of assignement
     */
    public function createDenormalizationStatement(Context $context, Expr $input)
    {
        return [[], $this->createDenormalizationValueStatement($context, $input)];
    }

    /**
     * Return the normalization used for this type
     *
     * @param Context  $context
     * @param Expr     $input
     *
     * @return Expr[], Expr First array contain all the expr need to transform the input value, second statement is the expr of assignement
     */
    public function createNormalizationStatement(Context $context, Expr $input)
    {
        return [[], $this->createNormalizationValueStatement($context, $input)];
    }

    /**
     * Create the denormalization Value Statement (Expr of assignement)
     *
     * @param Context  $context
     * @param Expr     $input
     *
     * @return Expr
     */
    protected function createDenormalizationValueStatement(Context $context, Expr $input)
    {
        return $input;
    }

    /**
     * Create the normalization Value Statement (Expr of assignement)
     *
     * @param Context  $context
     * @param Expr     $input
     *
     * @return Expr
     */
    protected function createNormalizationValueStatement(Context $context, Expr $input)
    {
        return $input;
    }

    /**
     * Create the condition Statement
     *
     * @param Expr $input
     *
     * @return Expr
     */
    public function createConditionStatement(Expr $input)
    {
        return new Expr\FuncCall(
            new Name($this->conditionMapping[$this->name]),
            [
                new Arg($input)
            ]
        );
    }

    /**
     * Create the condition Statement
     *
     * @param Expr $input
     *
     * @return Expr
     */
    public function createNormalizationConditionStatement(Expr $input)
    {
        return new Expr\FuncCall(
            new Name($this->normalizationConditionMapping[$this->name]),
            [
                new Arg($input)
            ]
        );
    }

    /**
     * Create the typehint statement
     *
     * @return null|string|Name
     */
    public function getTypeHint()
    {
        return null;
    }
}
