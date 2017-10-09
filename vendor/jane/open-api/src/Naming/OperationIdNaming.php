<?php

namespace Joli\Jane\OpenApi\Naming;

use Doctrine\Common\Inflector\Inflector;
use Joli\Jane\OpenApi\Operation\Operation;

class OperationIdNaming implements OperationNamingInterface
{
    public function generateFunctionName(Operation $operation)
    {
        return Inflector::camelize($operation->getOperation()->getOperationId());
    }
}
