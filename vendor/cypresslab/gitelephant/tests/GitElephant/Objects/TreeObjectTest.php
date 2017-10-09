<?php

namespace GitElephant\Objects;

use GitElephant\TestCase;

class TreeObjectTest extends TestCase
{
    public function setUp()
    {
        $this->initRepository();
        $this->getRepository()->init();
        $this->addFolder('test');
        $this->addFile('test-file', 'test');
        $this->getRepository()->commit('first commit', true);
    }

    public function testInstance()
    {
        $tree = $this->getRepository()->getTree('master', 'test');
        $this->assertInstanceOf('GitElephant\Objects\TreeObject', $tree[0]);
    }
}
