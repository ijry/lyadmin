<?php

namespace Joli\Jane\Generator;

use PhpParser\Node;

/**
 * File generated
 *
 * Simply a correspondance between a filename and a AST
 */
class File
{
    /**
     * Relative path of the file generated
     *
     * @var string
     */
    private $filename;

    /**
     * Ast generated
     *
     * @var Node
     */
    private $node;

    /**
     * Type of generation (model / normalizer / ...)
     *
     * @var string
     */
    private $type;

    public function __construct($filename, Node $node, $type)
    {
        $this->filename = $filename;
        $this->node     = $node;
        $this->type     = $type;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
} 
