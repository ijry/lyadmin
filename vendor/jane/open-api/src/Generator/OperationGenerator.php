<?php

namespace Joli\Jane\OpenApi\Generator;

use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Runtime\Reference;
use Joli\Jane\Reference\Resolver;
use Joli\Jane\OpenApi\Generator\Parameter\BodyParameterGenerator;
use Joli\Jane\OpenApi\Generator\Parameter\FormDataParameterGenerator;
use Joli\Jane\OpenApi\Generator\Parameter\HeaderParameterGenerator;
use Joli\Jane\OpenApi\Generator\Parameter\PathParameterGenerator;
use Joli\Jane\OpenApi\Generator\Parameter\QueryParameterGenerator;
use Joli\Jane\OpenApi\Operation\Operation;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Scalar;
use PhpParser\Comment;

class OperationGenerator
{
    use OutputGeneratorTrait;
    use InputGeneratorTrait;

    /**
     * @var Resolver
     */
    protected $resolver;

    public function __construct(Resolver $resolver, BodyParameterGenerator $bodyParameterGenerator, FormDataParameterGenerator $formDataParameterGenerator, HeaderParameterGenerator $headerParameterGenerator, PathParameterGenerator $pathParameterGenerator, QueryParameterGenerator $queryParameterGenerator)
    {
        $this->resolver                   = $resolver;
        $this->bodyParameterGenerator     = $bodyParameterGenerator;
        $this->formDataParameterGenerator = $formDataParameterGenerator;
        $this->headerParameterGenerator   = $headerParameterGenerator;
        $this->pathParameterGenerator     = $pathParameterGenerator;
        $this->queryParameterGenerator    = $queryParameterGenerator;
    }

    /**
     * Generate a method for an operation
     *
     * @param string    $name
     * @param Operation $operation
     * @param Context   $context
     *
     * @return Stmt\ClassMethod
     */
    public function generate($name, Operation $operation, Context $context)
    {
        // Input
        list($queryParamDocumentation, $queryParamStatements, $queryParamVariable) = $this->createQueryParamStatements($operation);
        list($documentationParameters, $parameters) = $this->createParameters($operation, $queryParamDocumentation, $context);
        list($urlStatements, $urlVariable) = $this->createUrlStatements($operation, $queryParamVariable);
        list($bodyStatements, $bodyVariable) = $this->createBodyStatements($operation, $queryParamVariable, $context);
        list($headerStatements, $headerVariable) = $this->createHeaderStatements($operation, $queryParamVariable);

        $statements = array_merge($queryParamStatements, $urlStatements, $headerStatements, $bodyStatements, [
            // $request = $this->messageFactory->createRequest('method', $url, $headers, $body);
            new Expr\Assign(new Expr\Variable('request'), new Expr\MethodCall(
                new Expr\PropertyFetch(new Expr\Variable('this'), 'messageFactory'),
                'createRequest',
                [
                    new Arg(new Scalar\String_($operation->getMethod())),
                    new Arg($urlVariable),
                    new Arg($headerVariable),
                    new Arg($bodyVariable)
                ]
            )),
            // $response = $this->httpClient->sendRequest($request);
            new Expr\Assign(new Expr\Variable('promise'), new Expr\MethodCall(
                new Expr\PropertyFetch(new Expr\Variable('this'), 'httpClient'),
                'sendAsyncRequest',
                [new Arg(new Expr\Variable('request'))]
            )),
            // if ($fetch === self::FETCH_PROMISE) { return $promise }
            new Stmt\If_(
                new Expr\BinaryOp\Identical(new Expr\ConstFetch(new Name('self::FETCH_PROMISE')), new Expr\Variable('fetch')),
                [
                    'stmts' => [
                        new Stmt\Return_(new Expr\Variable('promise'))
                    ]
                ]
            ),
            // $response = $promise->wait();
            new Expr\Assign(new Expr\Variable('response'), new Expr\MethodCall(
                new Expr\Variable('promise'),
                'wait'
            )),
        ]);

        // Output
        $outputStatements = [];
        $outputTypes = ["\\Psr\\Http\\Message\\ResponseInterface"];

        foreach ($operation->getOperation()->getResponses() as $status => $response) {
            if ($response instanceof Reference) {
                $response = $this->resolver->resolve($response);
            }

            list($outputType, $ifStatus) = $this->createResponseDenormalizationStatement($status, $response->getSchema(), $context);

            if (null !== $outputType) {
                if (!in_array($outputType, $outputTypes)) {
                    $outputTypes[] = $outputType;
                }

                $outputStatements[] = $ifStatus;
            }
        }

        if (!empty($outputStatements)) {
            $statements[] = new Stmt\If_(
                new Expr\BinaryOp\Equal(new Expr\ConstFetch(new Name('self::FETCH_OBJECT')), new Expr\Variable('fetch')),
                [
                    'stmts' => $outputStatements
                ]
            );
        }

        // return $response
        $statements[] = new Stmt\Return_(new Expr\Variable('response'));
        $documentation = array_merge(
            [
                '/**',
                sprintf(" * %s", $operation->getOperation()->getDescription()),
                ' *',
            ],
            $documentationParameters,
            [
                ' *',
                ' * @return ' . implode('|', $outputTypes),
                ' */'
            ]
        );

        return new Stmt\ClassMethod($name, [
            'type'     => Stmt\Class_::MODIFIER_PUBLIC,
            'params'   => $parameters,
            'stmts'    => $statements
        ], [
            'comments' => [new Comment\Doc(implode("\n", $documentation))]
        ]);
    }

    /**
     * @return Resolver
     */
    protected function getResolver()
    {
        return $this->resolver;
    }
}
