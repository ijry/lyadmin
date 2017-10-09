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

namespace GitElephant\Objects;

use \GitElephant\Command\RemoteCommand;
use \GitElephant\Repository;

/**
 * Class Remote
 *
 * An object representing a git remote
 *
 * @package GitElephant\Objects
 * @author  David Neimeyer <davidneimeyer@gmail.com>
 */
class Remote
{
    /**
     * @var \GitElephant\Repository
     */
    private $repository;

    /**
     * remote name
     *
     * @var string
     */
    private $name;

    /**
     * fetch url of named remote
     * @var string
     */
    private $fetchURL = '';

    /**
     * push url of named remote
     * @var string
     */
    private $pushURL = '';

    /**
     * HEAD branch of named remote
     * @var string
     */
    private $remoteHEAD = null;

    /**
     * @var array
     */
    private $branches;

    /**
     * Class constructor
     *
     * @param \GitElephant\Repository $repository   repository instance
     * @param string                  $name         remote name
     * @param bool                    $queryRemotes Do not fetch new information from remotes
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @return \GitElephant\Objects\Remote
     */
    public function __construct(Repository $repository, $name = null, $queryRemotes = true)
    {
        $this->repository = $repository;
        if ($name) {
            $this->name = trim($name);
            $this->createFromCommand($queryRemotes);
        }

        return $this;
    }

    /**
     * Static constructor
     *
     * @param \GitElephant\Repository $repository   repository instance
     * @param string                  $name         remote name
     * @param bool                    $queryRemotes Fetch new information from remotes
     *
     * @return \GitElephant\Objects\Remote
     */
    public static function pick(Repository $repository, $name = null, $queryRemotes = true)
    {
        return new self($repository, $name, $queryRemotes);
    }

    /**
     * get output lines from git-remote --verbose
     *
     * @param RemoteCommand $remoteCmd Optionally provide RemoteCommand object
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return array
     */
    public function getVerboseOutput(RemoteCommand $remoteCmd = null)
    {
        if (!$remoteCmd) {
            $remoteCmd = RemoteCommand::getInstance($this->repository);
        }
        $command = $remoteCmd->verbose();

        return $this->repository->getCaller()->execute($command)->getOutputLines(true);
    }

    /**
     * get output lines from git-remote show [name]
     *
     * NOTE: for technical reasons $name is optional, however under normal
     * implementation it SHOULD be passed!
     *
     * @param string        $name         Name of remote to show details
     * @param RemoteCommand $remoteCmd    Optionally provide RemoteCommand object
     * @param bool          $queryRemotes Do not fetch new information from remotes
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return array
     */
    public function getShowOutput($name = null, RemoteCommand $remoteCmd = null, $queryRemotes = true)
    {
        if (!$remoteCmd) {
            $remoteCmd = RemoteCommand::getInstance($this->repository);
        }
        $command = $remoteCmd->show($name, $queryRemotes);

        return $this->repository->getCaller()->execute($command)->getOutputLines(true);
    }

    /**
     * get/store the properties from git-remote command
     *
     * NOTE: the name property should be set if this is to do anything,
     * otherwise it's likely to throw
     *
     * @param bool $queryRemotes Do not fetch new information from remotes
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     * @return \GitElephant\Objects\Remote
     */
    private function createFromCommand($queryRemotes = true)
    {
        $outputLines = $this->getVerboseOutput();
        $list = array();
        foreach ($outputLines as $line) {
            $matches = static::getMatches($line);
            if (isset($matches[1])) {
                $list[] = $matches[1];
            }
        }
        array_filter($list);
        if (in_array($this->name, $list)) {
            $remoteDetails = $this->getShowOutput($this->name, null, $queryRemotes);
            $this->parseOutputLines($remoteDetails);
        } else {
            throw new \InvalidArgumentException(sprintf('The %s remote doesn\'t exists', $this->name));
        }

        return $this;
    }

    /**
     * parse details from remote show
     *
     * @param array|string $remoteDetails Output lines for a remote show
     *
     * @throws \UnexpectedValueException
     */
    public function parseOutputLines(Array $remoteDetails)
    {
        array_filter($remoteDetails);
        $name = array_shift($remoteDetails);
        $name = (is_string($name)) ? trim($name) : '';
        $name = $this->parseName($name);
        if (!$name) {
            throw new \UnexpectedValueException(sprintf('Invalid data provided for remote detail parsing'));
        }
        $this->name = $name;
        $fetchURLPattern = '/^Fetch\s+URL:\s*(.*)$/';
        $fetchURL = null;

        $pushURLPattern = '/^Push\s+URL:\s*(.*)$/';
        $pushURL = null;

        $remoteHEADPattern = '/^HEAD\s+branch:\s*(.*)$/';
        $remoteHEAD = null;

        $remoteBranchHeaderPattern = '/^Remote\s+branch(?:es)?:$/';
        $localBranchPullHeaderPattern = '/^Local\sbranch(?:es)?\sconfigured\sfor\s\'git\spull\'\:$/';
        $localRefPushHeaderPattern = '/^Local\sref(?:s)?\sconfigured\sfor\s\'git\spush\':$/';
        $groups = array(
            'remoteBranches'=>null,
            'localBranches'=>null,
            'localRefs'=>null,
        );

        foreach ($remoteDetails as $lineno => $line) {
            $line = trim($line);
            $matches = array();
            if (is_null($fetchURL) && preg_match($fetchURLPattern, $line, $matches)) {
                $this->fetchURL = $fetchURL = $matches[1];
            } elseif (is_null($pushURL) && preg_match($pushURLPattern, $line, $matches)) {
                $this->pushURL = $pushURL = $matches[1];
            } elseif (is_null($remoteHEAD) && preg_match($remoteHEADPattern, $line, $matches)) {
                $this->remoteHEAD = $remoteHEAD = $matches[1];
            } elseif (is_null($groups['remoteBranches']) && preg_match($remoteBranchHeaderPattern, $line, $matches)) {
                $groups['remoteBranches'] = $lineno;
            } elseif (is_null($groups['localBranches']) && preg_match($localBranchPullHeaderPattern, $line, $matches)) {
                $groups['localBranches'] = $lineno;
            } elseif (is_null($groups['localRefs']) && preg_match($localRefPushHeaderPattern, $line, $matches)) {
                $groups['localRefs'] = $lineno;
            }
        }

        $this->setBranches($this->aggregateBranchDetails($groups, $remoteDetails));
    }

    /**
     * provided with the start points of the branch details, parse out the
     * branch details and return a structured representation of said details
     *
     * @param array $groupLines    Associative array whose values are line numbers
     * are respective of the "group" detail present in $remoteDetails
     * @param array $remoteDetails Output of git-remote show [name]
     *
     * @return array
     */
    protected function aggregateBranchDetails($groupLines, $remoteDetails)
    {
        $configuredRefs = array();
        arsort($groupLines);
        foreach ($groupLines as $type => $lineno) {
            $configuredRefs[$type] = array_splice($remoteDetails, $lineno);
            array_shift($configuredRefs[$type]);
        }
        $configuredRefs['remoteBranches'] = (isset($configuredRefs['remoteBranches'])) ?
            $this->parseRemoteBranches($configuredRefs['remoteBranches']) : array();
        $configuredRefs['localBranches'] = (isset($configuredRefs['localBranches'])) ?
            $this->parseLocalPullBranches($configuredRefs['localBranches']) : array();
        $configuredRefs['localRefs'] = (isset($configuredRefs['localRefs'])) ?
            $this->parseLocalPushRefs($configuredRefs['localRefs']) : array();
        $aggBranches = array();
        foreach ($configuredRefs as $branches) {
            foreach ($branches as $branchName => $data) {
                if (!isset($aggBranches[$branchName])) {
                    $aggBranches[$branchName] = array();
                }
                $aggBranches[$branchName] = $aggBranches[$branchName] + $data;
            }
        }

        return $aggBranches;
    }

    /**
     * parse the details related to remote branch references
     *
     * @param array $lines
     *
     * @return array
     */
    public function parseRemoteBranches($lines)
    {
        $branches = array();
        $delimiter = ' ';
        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/\s+/', ' ', $line);
            $parts = explode($delimiter, $line);
            if (count($parts) > 1) {
                $branches[$parts[0]] = array( 'local_relationship' => $parts[1]);
            }
        }

        return $branches;
    }

    /**
     * parse the details related to local branches and the remotes that they
     * merge with
     *
     * @param array $lines
     *
     * @return array
     */
    public function parseLocalPullBranches($lines)
    {
        $branches = array();
        $delimiter = ' merges with remote ';
        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/\s+/', ' ', $line);
            $parts = explode($delimiter, $line);
            if (count($parts) > 1) {
                $branches[$parts[0]] = array('merges_with' => $parts[1]);
            }
        }

        return $branches;
    }

    /**
     * parse the details related to local branches and the remotes that they
     * push to
     *
     * @param array $lines
     *
     * @return array
     */
    public function parseLocalPushRefs($lines)
    {
        $branches = array();
        $delimiter = ' pushes to ';
        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/\s+/', ' ', $line);
            $parts = explode($delimiter, $line);
            if (count($parts) > 1) {
                $value = explode(' ', $parts[1], 2);
                $branches[$parts[0]] = array( 'pushes_to' => $value[0], 'local_state' => $value[1]);
            }
        }

        return $branches;
    }

    /**
     * parse remote name from git-remote show [name] output line
     *
     * @param string $line
     *
     * @return string remote name or blank if invalid
     */
    public function parseName($line)
    {
        $matches = array();
        $pattern = '/^\*\s+remote\s+(.*)$/';
        preg_match($pattern, trim($line), $matches);
        if (!isset($matches[1])) {
            return '';
        }

        return $matches[1];
    }

    /**
     * get the matches from an output line
     *
     * @param string $remoteString remote line output
     *
     * @throws \InvalidArgumentException
     * @return array
     */
    public static function getMatches($remoteString)
    {
        $matches = array();
        preg_match('/^(\S+)\s*(\S[^\( ]+)\s*\((.+)\)$/', trim($remoteString), $matches);
        if (!count($matches)) {
            throw new \InvalidArgumentException(sprintf('the remote string is not valid: %s', $remoteString));
        }

        return array_map('trim', $matches);
    }

    /**
     * toString magic method
     *
     * @return string the named remote
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * name setter
     *
     * @param string $name the remote name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * fetchURL getter
     *
     * @return string
     */
    public function getFetchURL()
    {
        return $this->fetchURL;
    }

    /**
     * fetchURL setter
     *
     * @param string $url the fetch url
     */
    public function setFetchURL($url)
    {
        $this->fetchURL = $url;
    }

    /**
     * pushURL getter
     *
     * @return string
     */
    public function getPushURL()
    {
        return $this->pushURL;
    }

    /**
     * pushURL setter
     *
     * @param string $url the push url
     */
    public function setPushURL($url)
    {
        $this->pushURL = $url;
    }

    /**
     * remote HEAD branch getter
     *
     * @return string
     */
    public function getRemoteHEAD()
    {
        return $this->remoteHEAD;
    }

    /**
     * remote HEAD branch setter
     *
     * @param string $branchName
     */
    public function setRemoteHEAD($branchName)
    {
        $this->remoteHEAD = $branchName;
    }

    /**
     * get structured representation of branches
     *
     * @return array
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * set structured representation of branches
     *
     * @param array $branches
     */
    public function setBranches(Array $branches)
    {
        $this->branches = $branches;
    }
}
