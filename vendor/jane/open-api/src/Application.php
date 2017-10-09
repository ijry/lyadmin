<?php

namespace Joli\Jane\OpenApi;

use Joli\Jane\OpenApi\Command\GenerateCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('Jane OpenApi', JaneOpenApi::VERSION);

        $this->add(new GenerateCommand());
    }

    public function getLongVersion()
    {
        $version = parent::getLongVersion();
        $commit  = '@git-commit@';

        if ('@'.'git-commit@' !== $commit) {
            $version .= ' ('.substr($commit, 0, 7).')';
        }

        return $version;
    }
}
