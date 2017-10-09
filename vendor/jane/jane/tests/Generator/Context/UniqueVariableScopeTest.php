<?php

namespace Joli\Jane\Generator\Context\Tests;

use Joli\Jane\Generator\Context\UniqueVariableScope;

class UniqueVariableScopeTest extends \PHPUnit_Framework_TestCase
{
    public function testUniqueVariable()
    {
        $uniqueVariableScope = new UniqueVariableScope();

        $name = $uniqueVariableScope->getUniqueName('name');
        $this->assertEquals('name', $name);

        $name = $uniqueVariableScope->getUniqueName('name');
        $this->assertEquals('name_1', $name);

        $name = $uniqueVariableScope->getUniqueName('name');
        $this->assertEquals('name_2', $name);
    }
}
