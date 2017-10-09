<?php

namespace Joli\Jane\OpenApi\Generator;

use Doctrine\Common\Inflector\Inflector;
use Joli\Jane\Generator\Context\Context;
use Joli\Jane\Runtime\Reference;
use Joli\Jane\OpenApi\Model\BodyParameter;
use Joli\Jane\OpenApi\Model\FormDataParameterSubSchema;
use Joli\Jane\OpenApi\Model\HeaderParameterSubSchema;
use Joli\Jane\OpenApi\Model\PathParameterSubSchema;
use Joli\Jane\OpenApi\Model\QueryParameterSubSchema;
use Joli\Jane\OpenApi\Operation\Operation;
use Joli\Jane\Reference\Resolver;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;

trait InputGeneratorTrait
{
    /**
     * @var Parameter\BodyParameterGenerator
     */
    protected $bodyParameterGenerator;

    /**
     * @var Parameter\FormDataParameterGenerator
     */
    protected $formDataParameterGenerator;

    /**
     * @var Parameter\HeaderParameterGenerator
     */
    protected $headerParameterGenerator;

    /**
     * @var Parameter\PathParameterGenerator
     */
    protected $pathParameterGenerator;

    /**
     * @var Parameter\QueryParameterGenerator
     */
    protected $queryParameterGenerator;

    /**
     * @return Resolver
     */
    abstract protected function getResolver();

    /**
     * Create the query param statements and documentation
     *
     * @param Operation $operation
     *
     * @return array
     */
    protected function createQueryParamStatements(Operation $operation)
    {
        $queryParamDocumentation = [];
        $queryParamVariable = new Expr\Variable('queryParam');
        $queryParamStatements = [
            new Expr\Assign($queryParamVariable, new Expr\New_(new Name('QueryParam')))
        ];

        if ($operation->getOperation()->getParameters()) {
            foreach ($operation->getOperation()->getParameters() as $parameter) {
                if ($parameter instanceof Reference) {
                    $parameter = $this->getResolver()->resolve($parameter);
                }

                if ($parameter instanceof FormDataParameterSubSchema) {
                    $queryParamStatements = array_merge($queryParamStatements, $this->formDataParameterGenerator->generateQueryParamStatements($parameter, $queryParamVariable));
                    $queryParamDocumentation[] = $this->formDataParameterGenerator->generateQueryDocParameter($parameter);
                }

                if ($parameter instanceof HeaderParameterSubSchema) {
                    $queryParamStatements = array_merge($queryParamStatements, $this->headerParameterGenerator->generateQueryParamStatements($parameter, $queryParamVariable));
                    $queryParamDocumentation[] = $this->headerParameterGenerator->generateQueryDocParameter($parameter);
                }

                if ($parameter instanceof QueryParameterSubSchema) {
                    $queryParamStatements = array_merge($queryParamStatements, $this->queryParameterGenerator->generateQueryParamStatements($parameter, $queryParamVariable));
                    $queryParamDocumentation[] = $this->queryParameterGenerator->generateQueryDocParameter($parameter);
                }
            }
        }

        return [$queryParamDocumentation, $queryParamStatements, $queryParamVariable];
    }

    /**
     * Create parameters for the method and their documentation
     *
     * @param Operation $operation
     * @param string[]  $queryParamDocumentation
     * @param Context   $context
     *
     * @return array
     */
    protected function createParameters(Operation $operation, $queryParamDocumentation, Context $context)
    {
        $documentationParams = [];
        $methodParameters = [];

        if ($operation->getOperation()->getParameters()) {
            foreach ($operation->getOperation()->getParameters() as $parameter) {
                if ($parameter instanceof Reference) {
                    $parameter = $this->getResolver()->resolve($parameter);
                }

                if ($parameter instanceof PathParameterSubSchema) {
                    $methodParameters[] = $this->pathParameterGenerator->generateMethodParameter($parameter, $context);
                    $documentationParams[] = sprintf(' * @param %s', $this->pathParameterGenerator->generateDocParameter($parameter, $context));
                }
            }

            foreach ($operation->getOperation()->getParameters() as $parameter) {
                if ($parameter instanceof Reference) {
                    $parameter = $this->getResolver()->resolve($parameter);
                }

                if ($parameter instanceof BodyParameter) {
                    $methodParameters[] = $this->bodyParameterGenerator->generateMethodParameter($parameter, $context);
                    $documentationParams[] = sprintf(' * @param %s', $this->bodyParameterGenerator->generateDocParameter($parameter, $context));
                }
            }
        }

        if (!empty($queryParamDocumentation)) {
            $documentationParams[] = " * @param array  \$parameters {";
            $documentationParams   = array_merge($documentationParams, array_map(function ($doc) {
                return " *     " . $doc;
            }, $queryParamDocumentation));
            $documentationParams[] = " * }";
        } else {
            $documentationParams[] = " * @param array  \$parameters List of parameters";
        }

        $documentationParams[] = " * @param string \$fetch      Fetch mode (object or response)";

        $methodParameters[] = new Param('parameters', new Expr\Array_());
        $methodParameters[] = new Param('fetch', new Expr\ConstFetch(new Name('self::FETCH_OBJECT')));

        return [$documentationParams, $methodParameters];
    }

    /**
     * Create all statements around url transformation
     *
     * @param Operation $operation
     * @param Expr\Variable $queryParamVariable
     *
     * @return array
     */
    protected function createUrlStatements(Operation $operation, $queryParamVariable)
    {
        $urlVariable = new Expr\Variable('url');
        // url = /path
        $statements = [
            new Expr\Assign($urlVariable, new Scalar\String_($operation->getPath()))
        ];

        if ($operation->getOperation()->getParameters()) {
            foreach ($operation->getOperation()->getParameters() as $parameter) {
                if ($parameter instanceof Reference) {
                    $parameter = $this->getResolver()->resolve($parameter);
                }

                if ($parameter instanceof PathParameterSubSchema) {
                    // $url = str_replace('{param}', $param, $url)
                    $statements[] = new Expr\Assign($urlVariable, new Expr\FuncCall(new Name('str_replace'), [
                        new Arg(new Scalar\String_('{' . $parameter->getName() . '}')),
                        new Arg(new Expr\FuncCall(new Name('urlencode'), [
                            new Arg(new Expr\Variable(Inflector::camelize($parameter->getName()))),
                        ])),
                        new Arg($urlVariable)
                    ]));
                }
            }
        }

        // url = url . ? . $queryParam->buildQueryString
        $statements[] = new Expr\Assign($urlVariable, new Expr\BinaryOp\Concat(
            $urlVariable,
            new Expr\BinaryOp\Concat(
                new Scalar\String_('?'),
                new Expr\MethodCall($queryParamVariable, 'buildQueryString', [new Arg(new Expr\Variable('parameters'))])
            )
        ));

        return [$statements, $urlVariable];
    }

    /**
     * Create body statements
     *
     * @param Operation $operation
     * @param Expr\Variable $queryParamVariable
     * @param Context $context
     *
     * @return array
     */
    protected function createBodyStatements(Operation $operation, $queryParamVariable, Context $context)
    {
        $bodyParameter = null;
        $bodyVariable = new Expr\Variable('body');

        if ($operation->getOperation()->getParameters()) {
            foreach ($operation->getOperation()->getParameters() as $parameter) {
                if ($parameter instanceof BodyParameter) {
                    $bodyParameter = $parameter;
                }
            }
        }

        if (null === $bodyParameter) {
            // $body = $queryParam->buildFormDataString($parameters);
            return [[
                new Expr\Assign($bodyVariable, new Expr\MethodCall($queryParamVariable, 'buildFormDataString', [new Arg(new Expr\Variable('parameters'))]))
            ], $bodyVariable];
        }

        // $body = $parameter
        if (!($bodyParameter->getSchema() instanceof Reference)) {
            return [[
                new Expr\Assign($bodyVariable, new Expr\Variable(Inflector::camelize($bodyParameter->getName())))
            ], $bodyVariable];
        }

        // $body = $this->serializer->serialize($parameter);
        return [
            [
                new Expr\Assign(
                    $bodyVariable,
                    new Expr\MethodCall(
                        new Expr\PropertyFetch(new Expr\Variable('this'), 'serializer'),
                        'serialize',
                        [
                            new Arg(new Expr\Variable(Inflector::camelize($bodyParameter->getName()))),
                            new Arg(new Scalar\String_('json'))
                        ]
                    )
                )
            ],
            $bodyVariable
        ];
    }

    /**
     * Create headers statements
     *
     * @param Operation $operation
     * @param Expr\Variable $queryParamVariable
     *
     * @return array
     */
    protected function createHeaderStatements(Operation $operation, $queryParamVariable)
    {
        $headerVariable = new Expr\Variable('headers');

        $headers = [
            new Expr\ArrayItem(
                new Scalar\String_($operation->getHost()),
                new Scalar\String_('Host')
            ),
        ];

        $produces = $operation->getOperation()->getProduces();

        if ($produces && in_array("application/json", $produces)) {
            $headers[]
                = new Expr\ArrayItem(
                    new Expr\Array_(
                        [
                        new Expr\ArrayItem(
                            new Scalar\String_("application/json")
                        ),
                        ]
                    ),
                    new Scalar\String_('Accept')
                );
        }

        $consumes = $operation->getOperation()->getProduces();

        if ($operation->getOperation()->getParameters() && $consumes) {
            $bodyParameters = array_filter(
                $operation->getOperation()->getParameters(),
                function ($parameter) {
                    return $parameter instanceof BodyParameter;
                }
            );

            if (count($bodyParameters) > 0 && in_array("application/json", $consumes)) {
                $headers[]
                    = new Expr\ArrayItem(
                        new Scalar\String_("application/json"),
                        new Scalar\String_('Content-Type')
                    );
            }
        }

        return [
            [
                new Expr\Assign(
                    $headerVariable,
                    new Expr\FuncCall(new Name('array_merge'), [
                        new Arg(
                            new Expr\Array_(
                                $headers
                            )
                        ),
                        new Arg(new Expr\MethodCall($queryParamVariable, 'buildHeaders', [new Arg(new Expr\Variable('parameters'))]))
                    ])
                )
            ],
            $headerVariable
        ];
    }
}
