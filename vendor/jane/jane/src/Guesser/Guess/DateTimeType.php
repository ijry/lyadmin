<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Expr;

/**
 * Represent a DateTime type.
 */
class DateTimeType extends ObjectType
{
    /**
     * Format of the date to use.
     *
     * @var string
     */
    private $format;

    public function __construct($object, $format = \DateTime::RFC3339)
    {
        parent::__construct($object, '\DateTime', []);

        $this->format = $format;
    }

    /**
     * (@inheritDoc}.
     */
    protected function createDenormalizationValueStatement(Context $context, Expr $input)
    {
        // \DateTime::createFromFormat($format, $data)
        return new Expr\StaticCall(new Name('\DateTime'), 'createFromFormat', [
            new Arg(new Expr\ConstFetch(new Name('"'.$this->format.'"'))),
            new Arg($input),
        ]);
    }

    /**
     * (@inheritDoc}.
     */
    protected function createNormalizationValueStatement(Context $context, Expr $input)
    {
        // $object->format($format);
        return new Expr\MethodCall($input, 'format', [
            new Arg(new Expr\ConstFetch(new Name('"'.$this->format.'"'))),
        ]);
    }

    /**
     * (@inheritDoc}.
     */
    public function createConditionStatement(Expr $input)
    {
        return new Expr\BinaryOp\LogicalAnd(new Expr\FuncCall(
            new Name('is_string'), [
                new Arg($input),
            ]),
            new Expr\BinaryOp\NotIdentical(
                new Expr\ConstFetch(new Name('false')),
                new Expr\StaticCall(new Name('\DateTime'), 'createFromFormat', [
                    new Arg(new Expr\ConstFetch(new Name('"'.$this->format.'"'))),
                    new Arg($input),
                ])
            )
        );
    }
}
