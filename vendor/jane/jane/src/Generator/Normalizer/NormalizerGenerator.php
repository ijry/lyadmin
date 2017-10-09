<?php

namespace Joli\Jane\Generator\Normalizer;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Generator\Naming;
use Joli\Jane\Guesser\Guess\Property;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

trait NormalizerGenerator
{
    /**
     * The naming service.
     *
     * @return Naming
     */
    abstract protected function getNaming();

    protected function createNormalizerClass($name, $methods)
    {
        return new Stmt\Class_(
            new Name($this->getNaming()->getClassName($name)),
            [
                'stmts' => array_merge($methods),
                'implements' => [new Name('DenormalizerInterface'), new Name('NormalizerInterface')],
                'extends' => new Name('SerializerAwareNormalizer'),
            ]
        );
    }

    /**
     * Create method to check if denormalization is supported.
     *
     * @param string $modelFqdn Fully Qualified name of the model class denormalized
     *
     * @return Stmt\ClassMethod
     */
    protected function createSupportsNormalizationMethod($modelFqdn)
    {
        return new Stmt\ClassMethod('supportsNormalization', [
            'type' => Stmt\Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param('data'),
                new Param('format', new Expr\ConstFetch(new Name('null'))),
            ],
            'stmts' => [
                new Stmt\If_(
                    new Expr\Instanceof_(new Expr\Variable('data'), new Name('\\'.$modelFqdn)),
                    [
                        'stmts' => [
                            new Stmt\Return_(new Expr\ConstFetch(new Name('true'))),
                        ],
                    ]
                ),
                new Stmt\Return_(new Expr\ConstFetch(new Name('false'))),
            ],
        ]);
    }

    /**
     * Create the normalization method.
     *
     * @param $modelFqdn
     * @param Context $context
     * @param $properties
     *
     * @return Stmt\ClassMethod
     */
    protected function createNormalizeMethod($modelFqdn, Context $context, $properties)
    {
        $context->refreshScope();
        $dataVariable = new Expr\Variable('data');
        $statements = [
            new Expr\Assign($dataVariable, new Expr\New_(new Name('\\stdClass'))),
        ];

        /** @var Property $property */
        foreach ($properties as $property) {
            $propertyVar = new Expr\MethodCall(new Expr\Variable('object'), $this->getNaming()->getPrefixedMethodName('get', $property->getName()));
            list($normalizationStatements, $outputVar) = $property->getType()->createNormalizationStatement($context, $propertyVar);

            $normalizationStatements[] = new Expr\Assign(new Expr\PropertyFetch($dataVariable, sprintf("{'%s'}", $property->getName())), $outputVar);

            if ($property->isNullable()) {
                $statements = array_merge($statements, $normalizationStatements);

                continue;
            }

            $statements[] = new Stmt\If_(
                new Expr\BinaryOp\NotIdentical(new Expr\ConstFetch(new Name('null')), $propertyVar),
                [
                    'stmts' => $normalizationStatements,
                ]
            );
        }

        $statements[] = new Stmt\Return_($dataVariable);

        return new Stmt\ClassMethod('normalize', [
            'type' => Stmt\Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param('object'),
                new Param('format', new Expr\ConstFetch(new Name('null'))),
                new Param('context', new Expr\Array_(), 'array'),
            ],
            'stmts' => $statements,
        ]);
    }
}
