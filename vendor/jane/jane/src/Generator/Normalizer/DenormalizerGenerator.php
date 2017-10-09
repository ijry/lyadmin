<?php

namespace Joli\Jane\Generator\Normalizer;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Generator\Naming;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;

trait DenormalizerGenerator
{
    /**
     * The naming service.
     *
     * @return Naming
     */
    abstract protected function getNaming();

    /**
     * Create method to check if denormalization is supported.
     *
     * @param string $modelFqdn Fully Qualified name of the model class denormalized
     *
     * @return Stmt\ClassMethod
     */
    protected function createSupportsDenormalizationMethod($modelFqdn)
    {
        return new Stmt\ClassMethod('supportsDenormalization', [
            'type' => Stmt\Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param('data'),
                new Param('type'),
                new Param('format', new Expr\ConstFetch(new Name('null'))),
            ],
            'stmts' => [
                new Stmt\If_(
                    new Expr\BinaryOp\NotIdentical(new Expr\Variable('type'), new Scalar\String_($modelFqdn)),
                    [
                        'stmts' => [
                            new Stmt\Return_(new Expr\ConstFetch(new Name('false'))),
                        ],
                    ]
                ),
                new Stmt\Return_(new Expr\ConstFetch(new Name('true'))),
            ],
        ]);
    }

    /**
     * Create the denormalization method.
     *
     * @param $modelFqdn
     * @param Context $context
     * @param $properties
     *
     * @return Stmt\ClassMethod
     */
    protected function createDenormalizeMethod($modelFqdn, Context $context, $properties)
    {
        $context->refreshScope();
        $objectVariable = new Expr\Variable('object');
        $assignStatement = new Expr\Assign($objectVariable, new Expr\New_(new Name('\\'.$modelFqdn)));
        $statements = [$assignStatement];

        if ($this->useReference) {
            $statements = [
                new Stmt\If_(
                    new Expr\Isset_([new Expr\PropertyFetch(new Expr\Variable('data'), "{'\$ref'}")]),
                    [
                        'stmts' => [
                            new Stmt\Return_(new Expr\New_(new Name('Reference'), [
                                new Expr\PropertyFetch(new Expr\Variable('data'), "{'\$ref'}"),
                                new Expr\Ternary(new Expr\ArrayDimFetch(new Expr\Variable('context'), new Scalar\String_('rootSchema')), null, new Expr\ConstFetch(new Name('null'))),
                            ])),
                        ],
                    ]
                ),
                $assignStatement,
                new Stmt\If_(
                    new Expr\BooleanNot(new Expr\Isset_([new Expr\ArrayDimFetch(new Expr\Variable('context'), new Scalar\String_('rootSchema'))])),
                    [
                        'stmts' => [
                            new Expr\Assign(new Expr\ArrayDimFetch(new Expr\Variable('context'), new Scalar\String_('rootSchema')), $objectVariable),
                        ],
                    ]
                ),
            ];
        }

        foreach ($properties as $property) {
            $propertyVar = new Expr\PropertyFetch(new Expr\Variable('data'), sprintf("{'%s'}", $property->getName()));
            list($denormalizationStatements, $outputVar) = $property->getType()->createDenormalizationStatement($context, $propertyVar);

            $statements[] = new Stmt\If_(
                new Expr\FuncCall(new Name('property_exists'), [
                    new Arg(new Expr\Variable('data')),
                    new Arg(new Scalar\String_($property->getName())),
                ]), [
                    'stmts' => array_merge($denormalizationStatements, [
                        new Expr\MethodCall($objectVariable, $this->getNaming()->getPrefixedMethodName('set', $property->getName()), [
                            $outputVar,
                        ]),
                    ]),
                ]
            );
        }

        $statements[] = new Stmt\Return_($objectVariable);

        return new Stmt\ClassMethod('denormalize', [
            'type' => Stmt\Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param('data'),
                new Param('class'),
                new Param('format', new Expr\ConstFetch(new Name('null'))),
                new Param('context', new Expr\Array_(), 'array'),
            ],
            'stmts' => $statements,
        ]);
    }
}
