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

use \GitElephant\GitBinary;

/**
 * GitBinary Test
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class GitBinaryTest extends TestCase
{
    protected $path = '/path/to/binary';

    public function testConstructor()
    {
        $binary = new GitBinary($this->path);
        $this->assertEquals($this->path, $binary->getPath());
    }
}
