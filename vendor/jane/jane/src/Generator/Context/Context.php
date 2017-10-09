<?php

namespace Joli\Jane\Generator\Context;

use Joli\Jane\Generator\File;
use Joli\Jane\Guesser\Guess\ClassGuess;
use Joli\Jane\Model\JsonSchema;

/**
 * Context when generating a library base on a Schema
 */
class Context
{
    /**
     * Root namespace of the class generated
     *
     * @var string
     */
    private $namespace;

    /**
     * Directory where the code must be generated
     *
     * @var string
     */
    private $directory;

    /**
     * The root object use for generating the model,
     * This is used to resolve reference
     *
     * @var object
     */
    private $rootReference;

    /**
     * A reference of all class created for each object,
     * so passing the same object will use the existing class
     *
     * @var ClassGuess[] $objectClassMap
     */
    private $objectClassMap;

    /**
     * Files generated through the run
     *
     * @var File[]
     */
    private $files = [];

    /**
     * List of variables name used, allow to generate unique variable name
     *
     * @var string[]
     */
    private $variablesName = [];

    /**
     * Internal reference for generating unique variable name
     * (in a deterministic way, so the same run will give the same name)
     *
     * @var int
     */
    private $reference = 0;

    /**
     * Variable scope to have unique variable name per method
     *
     * @var UniqueVariableScope
     */
    private $variableScope;

    /**
     * @param mixed        $rootReference
     * @param string       $namespace
     * @param string       $directory
     * @param ClassGuess[] $objectClassMap
     */
    public function __construct($rootReference, $namespace, $directory, $objectClassMap)
    {
        $this->rootReference   = $rootReference;
        $this->namespace       = $namespace;
        $this->directory       = $directory;
        $this->objectClassMap  = $objectClassMap;
        $this->variableScope   = new UniqueVariableScope();
    }

    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return object
     */
    public function getRootReference()
    {
        return $this->rootReference;
    }

    /**
     * @return ClassGuess[]
     */
    public function getObjectClassMap()
    {
        return $this->objectClassMap;
    }

    /**
     * Refresh the unique variable scope for a context
     */
    public function refreshScope()
    {
        $this->variableScope = new UniqueVariableScope();
    }

    /**
     * Get a unique variable name
     *
     * @param string $prefix
     *
     * @return string
     */
    public function getUniqueVariableName($prefix = 'var')
    {
        return $this->variableScope->getUniqueName($prefix);
    }
}
