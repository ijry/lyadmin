<?php

namespace Joli\Jane\OpenApi\Operation;

use Joli\Jane\OpenApi\Model\Operation as OpenApiOperation;
use Joli\Jane\OpenApi\Model\PathItem;
use Joli\Jane\OpenApi\Model\OpenApi;

class OperationManager
{
    public function buildOperationCollection(OpenApi $openApi)
    {
        $operationCollection = new OperationCollection();
        $host = $openApi->getHost() === null ? 'localhost' : $openApi->getHost();

        foreach ($openApi->getPaths() as $path => $pathItem) {
            if ($pathItem instanceof PathItem) {
                if ($pathItem->getDelete() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getDelete(), $path, Operation::DELETE, $openApi->getBasePath(), $host));
                }

                if ($pathItem->getGet() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getGet(), $path, Operation::GET, $openApi->getBasePath(), $host));
                }

                if ($pathItem->getHead() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getHead(), $path, Operation::HEAD, $openApi->getBasePath(), $host));
                }

                if ($pathItem->getOptions() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getOptions(), $path, Operation::OPTIONS, $openApi->getBasePath(), $host));
                }

                if ($pathItem->getPatch() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getPatch(), $path, Operation::PATCH, $openApi->getBasePath(), $host));
                }

                if ($pathItem->getPost() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getPost(), $path, Operation::POST, $openApi->getBasePath(), $host));
                }

                if ($pathItem->getPut() instanceof OpenApiOperation) {
                    $operationCollection->addOperation(new Operation($pathItem->getPut(), $path, Operation::PUT, $openApi->getBasePath(), $host));
                }
            }
        }

        return $operationCollection;
    }
}
