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

use \GitElephant\TestCase;

/**
 * Class RemoteCommandTest
 *
 * @package GitElephant\Command
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */
class SubCommandCommandTest extends TestCase
{
    /**
     * verify SubCommandCommands behavior, which is slightly
     * different that BaseCommand, for more sophisticated needs
     */
    public function testGetCommand()
    {
        $cmdName = 'foo';
        $subOne = 'bar';
        $subTwo = 'baz';
        $subThree = 'blurg';
        $argOne = '--opt1';
        $argTwo = '--opt2';
        $argTwoValue = 'val2';
        $expected = "foo '$argOne' '$argTwo' '$argTwoValue' '$subOne' '$subTwo' '$subThree'";

        $subcmd = new SubCommandCommand();

        $rmeth = new \ReflectionMethod($subcmd, 'addCommandName');
        $rmeth->setAccessible(true);
        $rmeth->invoke($subcmd, $cmdName);

        $rmeth = new \ReflectionMethod($subcmd, 'addCommandSubject');
        $rmeth->setAccessible(true);
        $rmeth->invoke($subcmd, $subOne);

        $rmeth = new \ReflectionMethod($subcmd, 'addCommandSubject');
        $rmeth->setAccessible(true);
        $rmeth->invoke($subcmd, $subTwo);

        $rmeth = new \ReflectionMethod($subcmd, 'addCommandSubject');
        $rmeth->setAccessible(true);
        $rmeth->invoke($subcmd, $subThree);

        $rmeth = new \ReflectionMethod($subcmd, 'addCommandArgument');
        $rmeth->setAccessible(true);
        $rmeth->invoke($subcmd, $argOne);

        $rmeth = new \ReflectionMethod($subcmd, 'addCommandArgument');
        $rmeth->setAccessible(true);
        $rmeth->invoke($subcmd, array($argTwo, $argTwoValue));

        $actual = $subcmd->getCommand();
        $this->assertEquals(
            $expected,
            $actual,
            'getCommand() produces string made from subject stact and extracted args'
        );
    }
}
