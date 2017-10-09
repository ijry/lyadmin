<?php

namespace Joli\Jane\OpenApi\Naming;

use Joli\Jane\OpenApi\Operation\Operation;

interface OperationNamingInterface
{
    public function generateFunctionName(Operation $operation);
}
