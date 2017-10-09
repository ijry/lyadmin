<?php

namespace Joli\Jane\Guesser\Guess;

use Joli\Jane\Generator\Context\Context;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

class MapType extends ArrayType
{
    public function __construct($object, Type $itemType)
    {
        parent::__construct($object, $itemType, 'object');

        $this->itemType = $itemType;
    }

    /**
     * (@inheritDoc}
     */
    public function getTypeHint()
    {
        return new Name('\ArrayObject');
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

    /**
     * {@inheritDoc}
     */
    protected function createLoopOutputAssignement(Expr $valuesVar, $loopKeyVar)
    {
        return new Expr\ArrayDimFetch($valuesVar, $loopKeyVar);
    }

    /**
     * {@inheritDoc}
     */
    protected function createNormalizationLoopOutputAssignement(Expr $valuesVar, $loopKeyVar)
    {
        return new Expr\PropertyFetch($valuesVar, $loopKeyVar);
    }
}
