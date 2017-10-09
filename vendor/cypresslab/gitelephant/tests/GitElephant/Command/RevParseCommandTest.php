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

namespace GitElephant\Command;

use \GitElephant\Objects\Branch;
use \GitElephant\Repository;
use \GitElephant\TestCase;

/**
 * Class RevParseCommandTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class RevParseCommandTest extends TestCase
{
    public function testRevParse()
    {
        $c = RevParseCommand::getInstance();
        $this->assertEquals("rev-parse 'master'", $c->revParse('master'));
        $this->assertEquals("rev-parse '--all' 'master'", $c->revParse('master', array(RevParseCommand::OPTION_ALL)));
        $this->assertEquals("rev-parse '--all' '--abbrev-ref' 'master'", $c->revParse('master', array(
            RevParseCommand::OPTION_ALL,
            RevParseCommand::OPTION_ABBREV_REF
        )));
    }

    public function testRevParseIsBare()
    {
        $this->initRepository(null, 0);
        $repo = $this->getRepository(0);
        $repo->init();

        $options = array(RevParseCommand::OPTION_IS_BARE_REPOSIORY);
        $c = RevParseCommand::getInstance()->revParse(null, $options);

        $caller = $repo->getCaller();
        $caller->execute($c);
        $this->assertEquals(array('false'), $caller->getOutputLines(true));

        $this->initRepository(null, 1);
        $repo = $this->getRepository(1);
        $repo->init(true);

        $caller = $repo->getCaller();
        $caller->execute($c);
        $this->assertEquals(array('true'), $caller->getOutputLines(true));
    }
}
