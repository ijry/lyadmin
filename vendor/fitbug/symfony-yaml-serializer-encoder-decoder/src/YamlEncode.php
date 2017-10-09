<?php

namespace Fitbug\SymfonySerializer\YamlEncoderDecoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\scalar;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Yaml\Yaml;

class YamlEncode implements EncoderInterface
{
    const OPTION_OBJECT                    = 'yaml_encode_object';
    const OPTION_EXCEPTION_ON_INVALID_TYPE = 'yaml_encode_exception_on_invalid_type';
    const OPTION_OBJECT_FOR_MAP            = 'yaml_encode_object_for_map';
    const OPTION_MULTI_LINE_LITERAL_BLOCK  = 'yaml_encode_multi_line_literal_block';
    const OPTION_INLINE                    = 'yaml_encode_inline';
    const OPTION_INDENT                    = 'yaml_encode_indent';
    const SUPPORTED_ENCODING_YAML          = 'yaml';

    /**
     * @var bool
     */
    private $multiLineLiteralBlock;

    /**
     * @var bool
     */
    private $exceptionOnInvalidType;

    /**
     * @var bool
     */
    private $objectForMap;

    /**
     * @var bool
     */
    private $object;
    /**
     * @var int
     */
    private $indent;
    /**
     * @var int
     */
    private $inline;

    /**
     * Constructs a new YamlDecode instance.
     *
     * @param bool $object
     * @param bool $exceptionOnInvalidType
     * @param bool $objectForMap
     * @param bool $multiLineLiteralBlock
     * @param int  $inline
     * @param int  $indent
     */
    public function __construct(
        $object = false,
        $exceptionOnInvalidType = false,
        $objectForMap = false,
        $multiLineLiteralBlock = false,
        $inline = 2,
        $indent = 2
    ) {
        $this->object                 = $object;
        $this->exceptionOnInvalidType = $exceptionOnInvalidType;
        $this->objectForMap           = $objectForMap;
        $this->multiLineLiteralBlock  = $multiLineLiteralBlock;
        $this->indent                 = $indent;

        $this->inline = $inline;
    }

    /**
     * Encodes data into the given format.
     *
     * The only supported is yaml
     *
     * @param mixed  $data    Data to encode
     * @param string $format  Format name
     * @param array  $context options that normalizers/encoders have access to
     *
     * @return string
     *
     * @throws UnexpectedValueException
     */
    public function encode($data, $format, array $context = [])
    {
        $context = $this->resolveContext($context);

        if ($this->isYamlOldStyleInterface()) {
            $encodedData = Yaml::dump(
                $data,
                $context[ self::OPTION_INLINE ],
                $context[ self::OPTION_INDENT ],
                $context[ self::OPTION_EXCEPTION_ON_INVALID_TYPE ],
                $context[ self::OPTION_OBJECT ]
            );

        } else {
            $options = $this->contextToOptions($context);

            $encodedData = Yaml::dump(
                $data,
                $context[ self::OPTION_INLINE ],
                $context[ self::OPTION_INDENT ],
                $options
            );
        }

        return $encodedData;
    }

    /**
     * Merges the default options of the Yaml Encoder with the passed context.
     *
     * @param array $context
     *
     * @return array
     */
    private function resolveContext(array $context)
    {
        $defaultOptions = [
            self::OPTION_OBJECT                    => $this->object,
            self::OPTION_EXCEPTION_ON_INVALID_TYPE => $this->exceptionOnInvalidType,
            self::OPTION_OBJECT_FOR_MAP            => $this->objectForMap,
            self::OPTION_MULTI_LINE_LITERAL_BLOCK  => $this->multiLineLiteralBlock,
            self::OPTION_INLINE                    => $this->inline,
            self::OPTION_INDENT                    => $this->indent,
        ];

        return array_merge($defaultOptions, $context);
    }

    /**
     * Convert the context to options understood by the parser
     *
     * @param array $options
     *
     * @return int
     */
    private function contextToOptions(array $options)
    {
        $optionToBitMap = [
            self::OPTION_OBJECT                    => Yaml::DUMP_OBJECT,
            self::OPTION_EXCEPTION_ON_INVALID_TYPE => Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE,
            self::OPTION_OBJECT_FOR_MAP            => Yaml::DUMP_OBJECT_AS_MAP,
            self::OPTION_MULTI_LINE_LITERAL_BLOCK  => Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK,
        ];

        $bitMaskedOption = 0;

        foreach ($optionToBitMap as $option => $bitMask) {
            if ($options[ $option ]) {
                $bitMaskedOption = $bitMaskedOption | $bitMask;
            }
        }

        return $bitMaskedOption;
    }


    /**
     * Checks whether the serializer can encode to given format.
     *
     * The only supported format is yaml
     *
     * @param string $format format name
     *
     * @return bool
     */
    public function supportsEncoding($format)
    {
        return $format == self::SUPPORTED_ENCODING_YAML;
    }

    private function isYamlOldStyleInterface()
    {
        return !defined("Symfony\\Component\\Yaml\\Yaml::DUMP_OBJECT");
    }
}
