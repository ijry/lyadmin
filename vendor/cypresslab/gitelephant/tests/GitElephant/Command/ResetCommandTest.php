<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 3/2/16
 * Time: 1:11 PM
 */

namespace GitElephant\Command;

use GitElephant\Objects\Commit;
use \GitElephant\TestCase;

class ResetCommandTest extends TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        $this->initRepository();
        $this->getRepository()->init();
        $this->addFile('test');
        $this->getRepository()->commit('test', true);
        $this->getRepository()->createBranch('test', 'master');
    }

    public function testResetHard()
    {
        $rstc = ResetCommand::getInstance();
        $this->assertEquals("reset '--hard' 'dbeac'",$rstc->reset('dbeac',array(ResetCommand::OPTION_HARD)));
    }
}
