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

namespace GitElephant\Objects;

use \GitElephant\TestCase;
use \GitElephant\Objects\Author;

/**
 * AuthorTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class AuthorTest extends TestCase
{
    /**
     * testAuthor
     */
    public function testAuthor()
    {
        $author = new Author();
        $author->setEmail('foo@bar.com');
        $author->setName('foo');
        $this->assertEquals('foo@bar.com', $author->getEmail());
        $this->assertEquals('foo', $author->getName());
        $this->assertEquals('foo <foo@bar.com>', $author);
    }
}
