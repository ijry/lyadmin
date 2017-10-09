<?php

namespace Joli\Jane\OpenApi\SchemaParser;

use Joli\Jane\OpenApi\Exception\ParseFailureException;
use Joli\Jane\OpenApi\Model\OpenApi;
use Symfony\Component\Serializer\SerializerInterface;

class SchemaParser
{
    const OPEN_API_MODEL    = "Joli\\Jane\\OpenApi\\Model\\OpenApi";
    const EXCEPTION_MESSAGE = "Could not parse \"%s\", is it a valid specification?";
    const CONTENT_TYPE_JSON = 'json';
    const CONTENT_TYPE_YAML = 'yaml';
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SchemaParser constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /**
     * Parse an file into a OpenAPI Schema model
     *
     * @param string $openApiSpec
     *
     * @return OpenApi
     *
     * @throws ParseFailureException
     */
    public function parseSchema($openApiSpec)
    {
        $openApiSpecContents = file_get_contents($openApiSpec);
        $schemaClass         = self::OPEN_API_MODEL;
        $schema              = null;
        $jsonException       = null;
        $yamlException       = null;

        try {
            $schema = $this->serializer->deserialize(
                $openApiSpecContents,
                $schemaClass,
                self::CONTENT_TYPE_JSON
            );
        } catch (\Exception $exception) {
            $jsonException = $exception;
        }

        if (!$schema) {
            try {
                $schema = $this->serializer->deserialize(
                    $openApiSpecContents,
                    $schemaClass,
                    self::CONTENT_TYPE_YAML
                );
            } catch (\Exception $exception) {
                $yamlException = $exception;
            }

            if (!$schema) {
                throw new ParseFailureException(
                    sprintf(self::EXCEPTION_MESSAGE, $openApiSpec),
                    1,
                    $jsonException,
                    $yamlException
                );
            }
        }

        return $schema;
    }
}
