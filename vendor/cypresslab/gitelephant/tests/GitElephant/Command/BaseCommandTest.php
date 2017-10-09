<?php
/**
 * User: matteo
 * Date: 20/05/13
 * Time: 21.47
 * Just for fun...
 */


namespace GitElephant\Command;

use \GitElephant\Command\BaseCommand;
use \GitElephant\TestCase;
use \ReflectionClass;

/**
 * Class BaseCommandTest
 *
 * @package GitElephant\Command
 */
class BaseCommandTest extends TestCase
{
    /**
     * test class constructor
     *
     * @covers GitElephant\Command\BaseCommand::__construct
     */
    public function testConstructor()
    {
        $bc1 = new BaseCommand();
        $this->assertInstanceOf("\\GitElephant\\Command\\BaseCommand", $bc1);

        $repo = $this->getRepository();

        $configs   = array('one' => 1, 'two' => 2, 'three' => 3);
        $options   = array('A' => 'a', 'B' => 'b', 'C' => 'c');
        $arguments = array('first', 'second', 'third');

        foreach ($configs   as $configName   => $configValue)   { $repo->addGlobalConfig($configName, $configValue); }
        foreach ($options   as $optionName   => $optionValue)   { $repo->addGlobalOption($optionName, $optionValue); }
        foreach ($arguments as                  $argumentValue) { $repo->addGlobalCommandArgument($argumentValue);   }

        $bc2    = new BaseCommand($repo);
        $ref_bc = new ReflectionClass($bc2);

        $ref_bc_cfg_prop = $ref_bc->getProperty('globalConfigs');
        $ref_bc_cfg_prop->setAccessible(true);
        $this->assertSame($configs, $ref_bc_cfg_prop->getValue($bc2));

        $ref_bc_opt_prop = $ref_bc->getProperty('globalOptions');
        $ref_bc_opt_prop->setAccessible(true);
        $this->assertSame($options, $ref_bc_opt_prop->getValue($bc2));

        $ref_bc_arg_prop = $ref_bc->getProperty('globalCommandArguments');
        $ref_bc_arg_prop->setAccessible(true);
        $this->assertSame($arguments, $ref_bc_arg_prop->getValue($bc2));
    }

    /**
     * test static factory
     *
     * @covers GitElephant\Command\BaseCommand::getInstance
     */
    public function testGetInstance()
    {
        $bc = BaseCommand::getInstance();
        $this->assertInstanceOf("\\GitElephant\\Command\\BaseCommand", $bc);
    }

    public function testAddGlobalConfigs() {
        $configs = array('one' => 1, 'two' => 2, 'three' => 3);
        $bc      = BaseCommand::getInstance();
        $ref_bc  = new ReflectionClass($bc);

        $ref_bc_cfg_prop = $ref_bc->getProperty('globalConfigs');
        $ref_bc_cfg_prop->setAccessible(true);
        $this->assertEmpty($ref_bc_cfg_prop->getValue($bc));

        $ref_bc_cfg_meth = $ref_bc->getMethod('addGlobalConfigs');
        $ref_bc_cfg_meth->setAccessible(true);
        $ref_bc_cfg_meth->invoke($bc, $configs);
        $this->assertSame($configs, $ref_bc_cfg_prop->getValue($bc));
    }

    public function testAddGlobalOptions() {
        $options = array('one' => 1, 'two' => 2, 'three' => 3);
        $bc      = BaseCommand::getInstance();
        $ref_bc  = new ReflectionClass($bc);

        $ref_bc_opt_prop = $ref_bc->getProperty('globalOptions');
        $ref_bc_opt_prop->setAccessible(true);
        $this->assertEmpty($ref_bc_opt_prop->getValue($bc));

        $ref_bc_opt_meth = $ref_bc->getMethod('addGlobalOptions');
        $ref_bc_opt_meth->setAccessible(true);
        $ref_bc_opt_meth->invoke($bc, $options);
        $this->assertSame($options, $ref_bc_opt_prop->getValue($bc));
    }

    public function testAddGlobalCommandArguments() {
        $arguments = array('one', 'two', 'three');
        $bc        = BaseCommand::getInstance();
        $ref_bc    = new ReflectionClass($bc);

        $ref_bc_arg_prop = $ref_bc->getProperty('globalCommandArguments');
        $ref_bc_arg_prop->setAccessible(true);
        $this->assertEmpty($ref_bc_arg_prop->getValue($bc));

        $ref_bc_arg_meth = $ref_bc->getMethod('addGlobalCommandArgument');
        $ref_bc_arg_meth->setAccessible(true);
        foreach ($arguments as $argument) {
            $ref_bc_arg_meth->invoke($bc, $argument);
        }
        $this->assertSame($arguments, $ref_bc_arg_prop->getValue($bc));
    }

    public function testGetCommand() {
        $name   = 'command';
        $bc     = BaseCommand::getInstance();
        $ref_bc = new ReflectionClass($bc);

        $ref_bc_arg_prop = $ref_bc->getProperty('commandName');
        $ref_bc_arg_prop->setAccessible(true);
        $ref_bc_arg_prop->setValue($bc, $name);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCommand');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = $name;
        $actual   = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }

    /**
     * testGetCommandException
     */
    public function testGetCommandException()
    {
        $bc = BaseCommand::getInstance();
        $this->setExpectedException('RuntimeException');
        $this->fail($bc->getCommand());
    }

    public function testGetCLICommandArguments() {
        $args   = array('--first', '--second', '--third');
        $bc     = BaseCommand::getInstance();
        $ref_bc = new ReflectionClass($bc);

        $ref_bc_arg_prop = $ref_bc->getProperty('globalCommandArguments');
        $ref_bc_arg_prop->setAccessible(true);
        $ref_bc_arg_prop->setValue($bc, $args);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCLICommandArguments');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = '';
        foreach ($args as $argument) {
            $expected .= " '$argument'";
        }
        $actual   = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }

    public function testGetCLICommandName() {
        $name   = 'command';
        $bc     = BaseCommand::getInstance();
        $ref_bc = new ReflectionClass($bc);

        $ref_bc_arg_prop = $ref_bc->getProperty('commandName');
        $ref_bc_arg_prop->setAccessible(true);
        $ref_bc_arg_prop->setValue($bc, $name);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCLICommandName');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = " $name";
        $actual   = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }

    public function testGetCLIConfigs() {
        $globals = array('global.first' => 'a', 'global.second' => 'b');
        $locals  = array('local.first' => 'c', 'local.second' => 'd');
        $configs = array_merge($globals, $locals);

        $bc     = BaseCommand::getInstance();
        $ref_bc = new ReflectionClass($bc);

        $ref_bc_glob_cfg_prop = $ref_bc->getProperty('globalConfigs');
        $ref_bc_glob_cfg_prop->setAccessible(true);
        $ref_bc_glob_cfg_prop->setValue($bc, $globals);

        $ref_bc_loc_cfg_prop = $ref_bc->getProperty('configs');
        $ref_bc_loc_cfg_prop->setAccessible(true);
        $ref_bc_loc_cfg_prop->setValue($bc, $locals);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCLIConfigs');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = '';
        foreach ($configs as $name => $value) {
            $expected .= " '-c' '$name'='$value'";
        }
        $actual    = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }

    public function testGetCLIGlobalOptions() {
        $options = array('first' => 'a', 'second' => 'b', 'third' => 'c');
        $bc      = BaseCommand::getInstance();
        $ref_bc  = new ReflectionClass($bc);

        $ref_bc_opt_prop = $ref_bc->getProperty('globalOptions');
        $ref_bc_opt_prop->setAccessible(true);
        $ref_bc_opt_prop->setValue($bc, $options);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCLIGlobalOptions');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = '';
        foreach ($options as $name => $value) {
            $expected .= " '$name'='$value'";
        }
        $actual   = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }

    public function testGetCLIPath() {
        $path   = '/path/to/something';
        $bc     = BaseCommand::getInstance();
        $ref_bc = new ReflectionClass($bc);

        $ref_bc_path_prop = $ref_bc->getProperty('path');
        $ref_bc_path_prop->setAccessible(true);
        $ref_bc_path_prop->setValue($bc, $path);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCLIPath');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = " -- '$path'";
        $actual   = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }

    public function testGetCLISubjects() {
        $subject1 = 'first';
        $subject2 = 'second';
        $bc       = BaseCommand::getInstance();
        $ref_bc   = new ReflectionClass($bc);

        $ref_bc_subj1_prop = $ref_bc->getProperty('commandSubject');
        $ref_bc_subj1_prop->setAccessible(true);
        $ref_bc_subj1_prop->setValue($bc, $subject1);

        $ref_bc_subj2_prop = $ref_bc->getProperty('commandSubject2');
        $ref_bc_subj2_prop->setAccessible(true);
        $ref_bc_subj2_prop->setValue($bc, $subject2);

        $ref_bc_cli_meth = $ref_bc->getMethod('getCLISubjects');
        $ref_bc_cli_meth->setAccessible(true);

        $expected = " '$subject1' '$subject2'";
        $actual   = $ref_bc_cli_meth->invoke($bc);
        $this->assertSame($expected, $actual);
    }
}
