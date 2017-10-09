<?php

namespace Joli\Jane\Reference;

use Joli\Jane\Model\JsonSchema;
use Joli\Jane\Runtime\Reference;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\SerializerInterface;

class Resolver
{
    private $schemaCache = [];

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Resolve a JSON Reference for a Schema
     *
     * @param Reference $reference
     *
     * @throws UnsupportedException
     *
     * @return mixed Return the json value (deserialized) referenced
     */
    public function resolve(Reference $reference)
    {
        if ($reference->getResolved() === null) {
            $reference->setResolved($this->doResolve($reference));
        }

        return $reference->getResolved();
    }

    /**
     * Resolve a JSON Reference for a Schema
     *
     * @param Reference $reference
     *
     * @throws UnsupportedException
     *
     * @return mixed Return the json value (deserialized) referenced
     */
    protected function doResolve(Reference $reference)
    {
        $referencedSchema = $this->resolveSchema($reference, $reference->getCurrentSchema());

        if ($reference->hasFragment()) {
            $schema = $this->resolveJSONPointer($reference, $referencedSchema);
        } else {
            $schema = $referencedSchema;
        }

        if ($schema instanceof Reference) {
            return $this->resolve($schema, $referencedSchema);
        }

        return $schema;
    }

    /**
     * Resolve JSON Schema for the reference
     *
     * @param Reference $reference
     * @param JsonSchema $currentSchema
     *
     * @throws UnsupportedException
     *
     * @return JsonSchema Return the json schema referenced
     */
    protected function resolveSchema(Reference $reference, $currentSchema)
    {
        if ($reference->isInCurrentDocument() && $reference->hasFragment()) {
            return $currentSchema;
        }

        if ($reference->isRelative() && !$currentSchema->getId()) {
            throw new UnsupportedException(sprintf("Reference is relative and no id found in current schema, cannot resolve reference %s", $reference->getReference()));
        }

        // Build url
        $schemaUrl = sprintf('%s://%s:%s', $reference->getScheme() ?: 'http', $reference->getHost(), $reference->getPort() ?: '80');

        if ($reference->isRelative()) {
            $parsedUrl = parse_url($currentSchema->getId());
            $schemaUrl = sprintf('%s://%s:%s', $parsedUrl['scheme'] ?: 'http', $parsedUrl['host'], $parsedUrl['port'] ?: '80');
        }

        if ($reference->getPath()) {
            $schemaUrl = sprintf("%s/%s", $schemaUrl, $reference->getPath());
        }

        if ($reference->getQuery()) {
            $schemaUrl = sprintf("%s?%s", $schemaUrl, $reference->getQuery());
        }

        if (!isset($this->schemaCache[$schemaUrl])) {
            $schema = $this->serializer->deserialize($this->getJsonSchemaContent($schemaUrl), 'Joli\Jane\Model\JsonSchema', 'json');

            $this->schemaCache[$schemaUrl] = $schema;
        }

        return $this->schemaCache[$schemaUrl];
    }

    /**
     * Resolve a JSON Pointer for a Schema
     *
     * @param Reference  $reference
     * @param JsonSchema $schema
     *
     * @return mixed Return the json value (deserialized) referenced
     */
    protected function resolveJSONPointer(Reference $reference, $schema)
    {
        $pointer = $reference->getFragment();

        if (empty($pointer)) {
            return $schema;
        }

        // Separate pointer into tokens
        $tokens = explode('/', $pointer);
        //
        array_shift($tokens);
        // Unescape token
        $tokens = array_map(function ($token) {
            $token = str_replace('~0', '/', $token);
            $token = str_replace('~1', '~', $token);

            return $token;
        }, $tokens);

        $propertyPath     = implode(".", $tokens);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $propertyAccessor->getValue($schema, $propertyPath);
    }

    private function getJsonSchemaContent($schemaUrl)
    {
        return file_get_contents($schemaUrl);
    }
}
