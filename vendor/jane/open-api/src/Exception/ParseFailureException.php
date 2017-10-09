<?php

namespace Joli\Jane\OpenApi\Exception;

use Exception;
use UnexpectedValueException;

class ParseFailureException extends UnexpectedValueException
{
    /**
     * @var Exception
     */
    private $previousYaml;

    /**
     * Construct the exception.
     *
     * @param string    $message      [optional] The Exception message to throw.
     * @param int       $code         [optional] The Exception code.
     * @param Exception $previousJson [optional] The previous exception from the Json serialisation attempt
     * @param Exception $previousYaml [optional] The previous exception from the Yaml serialisation attempt
     */
    public function __construct(
        $message = "",
        $code = 0,
        Exception $previousJson = null,
        Exception $previousYaml = null
    ) {
        parent::__construct($message, $code, $previousJson);
        $this->previousYaml = $previousYaml;
    }

    /**
     * Get the previous exception from the Yaml serialisation attempt
     *
     * @return Exception
     */
    final public function getPreviousYaml()
    {
        return $this->previousYaml;
    }

    /**
     * Get the previous exception from the Json serialisation attempt
     *
     * @return Exception
     */
    final public function getPreviousJson()
    {
        return $this->getPrevious();
    }
}
