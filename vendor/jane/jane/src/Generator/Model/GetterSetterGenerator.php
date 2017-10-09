<?php

namespace Joli\Jane\Generator\Model;

use Joli\Jane\Generator\Naming;
use Joli\Jane\Guesser\Guess\Type;
use PhpParser\Comment\Doc;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

trait GetterSetterGenerator
{
    /**
     * The naming service
     *
     * @return Naming
     */
    abstract protected function getNaming();

    /**
     * Create get method.
     *
     * @param $name
     * @param Type $type
     *
     * @return Stmt\ClassMethod
     */
    protected function createGetter($name, Type $type)
    {
        return new Stmt\ClassMethod(
            // getProperty
            $this->getNaming()->getPrefixedMethodName('get', $name),
            [
                // public function
                'type' => Stmt\Class_::MODIFIER_PUBLIC,
                'stmts' => [
                    // return $this->property;
                    new Stmt\Return_(
                        new Expr\PropertyFetch(new Expr\Variable('this'), $this->getNaming()->getPropertyName($name))
                    ),
                ],
            ], [
                'comments' => [$this->createGetterDoc($type)],
            ]
        );
    }

    /**
     * Create set method.
     *
     * @param $name
     * @param Type $type
     *
     * @return Stmt\ClassMethod
     */
    protected function createSetter($name, Type $type)
    {
        return new Stmt\ClassMethod(
            // setProperty
            $this->getNaming()->getPrefixedMethodName('set', $name),
            [
                // public function
                'type' => Stmt\Class_::MODIFIER_PUBLIC,
                // ($property)
                'params' => [
                    new Param($this->getNaming()->getPropertyName($name), new Expr\ConstFetch(new Name('null')), $type->getTypeHint()),
                ],
                'stmts' => [
                    // $this->property = $property;
                    new Expr\Assign(
                        new Expr\PropertyFetch(
                            new Expr\Variable('this'),
                            $this->getNaming()->getPropertyName($name)
                        ), new Expr\Variable($this->getNaming()->getPropertyName($name))
                    ),
                    // return $this;
                    new Stmt\Return_(new Expr\Variable('this')),
                ],
            ], [
                'comments' => [$this->createSetterDoc($name, $type)],
            ]
        );
    }

    /**
     * Return doc for get method.
     *
     * @param Type $type
     *
     * @return Doc
     */
    protected function createGetterDoc(Type $type)
    {
        return new Doc(sprintf(<<<EOD
/**
 * @return %s
 */
EOD
        , $type->__toString()));
    }

    /**
     * Return doc for set method.
     *
     * @param $name
     * @param Type $type
     *
     * @return Doc
     */
    protected function createSetterDoc($name, Type $type)
    {
        return new Doc(sprintf(<<<EOD
/**
 * @param %s %s
 *
 * @return self
 */
EOD
        , $type->__toString(), '$'.$this->getNaming()->getPropertyName($name)));
    }
}
