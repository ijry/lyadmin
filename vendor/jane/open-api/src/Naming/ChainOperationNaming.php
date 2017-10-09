<?php

namespace Joli\Jane\OpenApi\Naming;

use Joli\Jane\OpenApi\Operation\Operation;

class ChainOperationNaming implements OperationNamingInterface
{
    /**
     * @var OperationNamingInterface[]
     */
    private $operationNamings;

    /**
     * @param OperationNamingInterface[] $operationNamings
     */
    public function __construct(array $operationNamings)
    {
        $this->operationNamings = $operationNamings;
    }

    public function generateFunctionName(Operation $operation)
    {
        foreach ($this->operationNamings as $operationNaming) {
            $functionName = $operationNaming->generateFunctionName($operation);

            if (!empty($functionName)) {
                return $functionName;
            }
        }

        return '';
    }
}
