<?php
/**
 * GitElephant - An abstraction layer for git written in PHP
 * Copyright (C) 2013  Matteo Giachino
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see [http://www.gnu.org/licenses/].
 */

namespace GitElephant\Command;

use \GitElephant\Repository;
use \PhpCollection\Map;

/**
 * BaseCommand
 *
 * The base class for all the command generators
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class BaseCommand
{
    /**
     * the command name
     *
     * @var string
     */
    private $commandName = null;

    /**
     * config options
     *
     * @var array
     */
    private $configs = array();

    /**
     * global configs
     *
     * @var array
     */
    private $globalConfigs = array();

    /**
     * global options
     *
     * @var array
     */
    private $globalOptions = array();

    /**
     * the command arguments
     *
     * @var array
     */
    private $commandArguments = array();

    /**
     * the global command arguments
     *
     * @var array
     */
    private $globalCommandArguments = array();

    /**
     * the command subject
     *
     * @var string|SubCommandCommand
     */
    private $commandSubject = null;

    /**
     * the command second subject (i.e. for branch)
     *
     * @var string|SubCommandCommand
     */
    private $commandSubject2 = null;

    /**
     * the path
     *
     * @var string
     */
    private $path = null;

    /**
     * constructor
     *
     * should be called by all child classes' constructors to permit use of 
     * global configs, options and command arguments
     *
     * @param null|\GitElephant\Repository $repo The repo object to read
     */
    public function __construct(Repository $repo = null)
    {
        if (!is_null($repo)) {
            $this->addGlobalConfigs($repo->getGlobalConfigs());
            $this->addGlobalOptions($repo->getGlobalOptions());
            
            $arguments = $repo->getGlobalCommandArguments();
            if (!empty($arguments)) {
                foreach ($arguments as $argument) {
                    $this->addGlobalCommandArgument($argument);
                }
            }
        }
    }

    /**
     * Clear all previous variables
     */
    public function clearAll()
    {
        $this->commandName            = null;
        $this->configs                = array();
        $this->commandArguments       = array();
        $this->commandSubject         = null;
        $this->commandSubject2        = null;
        $this->path                   = null;
    }

    public static function getInstance(Repository $repo = null)
    {
        return new static($repo);
    }

    /**
     * Add the command name
     *
     * @param string $commandName the command name
     */
    protected function addCommandName($commandName)
    {
        $this->commandName = $commandName;
    }

    /**
     * Get command name
     *
     * @return string
     */
    protected function getCommandName()
    {
        return $this->commandName;
    }

    /**
     * Set Configs
     *
     * @param array|Map $configs the config variable. i.e. { "color.status" => "false", "color.diff" => "true" }
     */
    public function addConfigs($configs)
    {
        foreach ($configs as $config => $value) {
            $this->configs[$config] = $value;
        }
    }

    /**
     * Set global configs
     *
     * @param array|Map $configs the config variable. i.e. { "color.status" => "false", "color.diff" => "true" }
     */
    protected function addGlobalConfigs($configs)
    {
        if (!empty($configs)) {
            foreach ($configs as $config => $value) {
                $this->globalConfigs[$config] = $value;
            }
        }
    }

    /**
     * Set global option
     *
     * @param array|Map $options a global option
     */
    protected function addGlobalOptions($options)
    {
        if (!empty($options)) {
            foreach ($options as $name => $value) {
                $this->globalOptions[$name] = $value;
            }
        }
    }

    /**
     * Get Configs
     *
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * Add a command argument
     *
     * @param string $commandArgument the command argument
     */
    protected function addCommandArgument($commandArgument)
    {
        $this->commandArguments[] = $commandArgument;
    }

    /**
     * Add a global command argument
     *
     * @param string $commandArgument the command argument
     */
    protected function addGlobalCommandArgument($commandArgument)
    {
        if (!empty($commandArgument)) {
            $this->globalCommandArguments[] = $commandArgument;
        }
    }

    /**
     * Get all added command arguments
     *
     * @return array
     */
    protected function getCommandArguments()
    {
        return ($this->commandArguments) ? $this->commandArguments: array();
    }

    /**
     * Add a command subject
     *
     * @param string $commandSubject the command subject
     */
    protected function addCommandSubject($commandSubject)
    {
        $this->commandSubject = $commandSubject;
    }

    /**
     * Add a second command subject
     *
     * @param string $commandSubject2 the second command subject
     */
    protected function addCommandSubject2($commandSubject2)
    {
        $this->commandSubject2 = $commandSubject2;
    }

    /**
     * Add a path to the git command
     *
     * @param string $path path
     */
    protected function addPath($path)
    {
        $this->path = $path;
    }

    /**
     * Normalize any valid option to its long name
     * an provide a structure that can be more intelligently
     * handled by other routines
     *
     * @param array $options       command options
     * @param array $switchOptions list of valid options that are switch like
     * @param array $valueOptions  list of valid options that must have a value assignment
     *
     * @return array Associative array of valid, normalized command options
     */
    public function normalizeOptions(Array $options = array(), Array $switchOptions = array(), $valueOptions = array())
    {
        $normalizedOptions = array();

        foreach ($options as $option) {
            if (array_key_exists($option, $switchOptions)) {
                $normalizedOptions[$switchOptions[$option]] = $switchOptions[$option];
            } else {
                $parts = preg_split('/([\s=])+/', $option, 2, PREG_SPLIT_DELIM_CAPTURE);
                if (count($parts)) {
                    $optionName = $parts[0];
                    if (in_array($optionName, $valueOptions)) {
                        $value = ($parts[1] == '=') ? $option : array($parts[0], $parts[2]);
                        $normalizedOptions[$optionName] = $value;
                    }
                }
            }
        }

        return $normalizedOptions;
    }

    /**
     * Get the current command
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getCommand()
    {
        if (is_null($this->commandName)) {
            throw new \RuntimeException("You should pass a commandName to execute a command");
        }

        $command  = '';
        $command .= $this->getCLIConfigs();
        $command .= $this->getCLIGlobalOptions();
        $command .= $this->getCLICommandName();
        $command .= $this->getCLICommandArguments();
        $command .= $this->getCLISubjects();
        $command .= $this->getCLIPath();

        $command = preg_replace('/\\s{2,}/', ' ', $command);

        return trim($command);
    }

    /**
     * get a string of CLI-formatted command arguments
     *
     * @return string The command argument string
     */
    private function getCLICommandArguments()
    {
        $command = '';
        $combinedArguments = array_merge($this->globalCommandArguments, $this->commandArguments);
        if (count($combinedArguments) > 0) {
            $command .= ' ' . implode(' ', array_map('escapeshellarg', $combinedArguments));
        }
        return $command;
    }

    /**
     * get a string of CLI-formatted command name
     *
     * @return string The command name string
     */
    private function getCLICommandName()
    {
        return ' ' . $this->commandName;
    }

    /**
     * get a string of CLI-formatted configs
     *
     * @return string The config string
     */
    private function getCLIConfigs()
    {
        $command = '';
        $combinedConfigs = array_merge($this->globalConfigs, $this->configs);
        if (count($combinedConfigs)) {
            foreach ($combinedConfigs as $config => $value) {
                $command .= sprintf(
                    ' %s %s=%s',
                    escapeshellarg('-c'),
                    escapeshellarg($config),
                    escapeshellarg($value)
                );
            }
        }
        return $command;
    }

    /**
     * get a string of CLI-formatted global options
     *
     * @return string The global options string
     */
    private function getCLIGlobalOptions()
    {
        $command = '';
        if (count($this->globalOptions) > 0) {
            foreach ($this->globalOptions as $name => $value) {
                $command .= sprintf(' %s=%s', escapeshellarg($name), escapeshellarg($value));
            }
        }
        return $command;
    }

    /**
     * get a string of CLI-formatted path
     *
     * @return string The path string
     */
    private function getCLIPath()
    {
        $command = '';
        if (!is_null($this->path)) {
            $command .= sprintf(' -- %s', escapeshellarg($this->path));
        }
        return $command;
    }

    /**
     * get a string of CLI-formatted subjects
     *
     * @throws \RuntimeException
     * @return string The subjects string
     */
    private function getCLISubjects()
    {
        $command = '';
        if (!is_null($this->commandSubject)) {
            $command .= ' ';
            if ($this->commandSubject instanceof SubCommandCommand) {
                $command .= $this->commandSubject->getCommand();
            } else {
                if (is_array($this->commandSubject)) {
                    $command .= implode(' ', array_map('escapeshellarg', $this->commandSubject));
                } else {
                    $command .= escapeshellarg($this->commandSubject);
                }
            }
        }
        if (!is_null($this->commandSubject2)) {
            $command .= ' ';
            if ($this->commandSubject2 instanceof SubCommandCommand) {
                $command .= $this->commandSubject2->getCommand();
            } else {
                if (is_array($this->commandSubject2)) {
                    $command .= implode(' ', array_map('escapeshellarg', $this->commandSubject2));
                } else {
                    $command .= escapeshellarg($this->commandSubject2);
                }
            }
        }
        return $command;
    }
}
