<?php

/**
 * This file is part of the GitElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Just for fun...
 */

namespace GitElephant;

/**
 * UtilitiesTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class UtilitiesTest extends TestCase
{
    protected $arr = array(
        'a',
        'b',
        'c',
        '1',
        'd',
        'b',
        'e'
    );

    /**
     * testNormalizeDirectorySeparator
     */
    public function testNormalizeDirectorySeparator()
    {
        $this->assertEquals('foo/bar', Utilities::normalizeDirectorySeparator('foo/bar'));
    }

    /**
     * @covers GitElephant\Utilities::pregSplitArray
     */
    public function testPregSplitArray()
    {
        $this->assertEquals(
            array(
                array('b', 'c', '1', 'd'),
                array('b', 'e')
            ),
            Utilities::pregSplitArray($this->arr, '/^b$/')
        );
        $this->assertEquals(
            array(
                array('1', 'd', 'b', 'e')
            ),
            Utilities::pregSplitArray($this->arr, '/^\d$/')
        );
    }

    public function testPregSplitFlatArray()
    {
        $this->assertEquals(
            array(
                array('a'),
                array('b', 'c', '1', 'd'),
                array('b', 'e')),
            Utilities::pregSplitFlatArray($this->arr, '/^b$/')
        );
    }

    /**
     * @covers GitElephant\Utilities::isAssociative
     */
    public function testIsAssociative()
    {
        $this->assertFalse(Utilities::isAssociative(array(1, 2)));
        $this->assertTrue(Utilities::isAssociative(array(1 => 1, 2 => 2)));
        $this->assertFalse(Utilities::isAssociative(array(0 => 1, 1 => 2)));
    }
}
