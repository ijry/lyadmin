<?php

namespace Fitbug\SymfonySerializer\YamlEncoderDecoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Yaml\Yaml;

class YamlDecode implements DecoderInterface
{
    const OPTION_EXCEPTION_ON_INVALID_TYPE = 'yaml_decode_exception_on_invalid_type';
    const OPTION_OBJECT                    = 'yaml_decode_object';
    const OPTION_OBJECT_FOR_MAP            = 'yaml_decode_object_for_map';
    const OPTION_DATE_TIME                 = 'yaml_decode_date_time';
    const SUPPORTED_ENCODING_YAML          = "yaml";
    /**
     * @var bool
     */
    private $exceptionOnInvalidType;
    /**
     * @var bool
     */
    private $object;
    /**
     * @var bool
     */
    private $objectForMap;
    /**
     * @var bool
     */
    private $dateTime;

    /**
     * Constructs a new YamlDecode instance.
     *
     * @param bool $exceptionOnInvalidType
     * @param bool $object
     * @param bool $objectForMap
     * @param bool $dateTime
     */
    public function __construct(
        $exceptionOnInvalidType = false,
        $object = false,
        $objectForMap = false,
        $dateTime = false
    ) {
        $this->exceptionOnInvalidType = $exceptionOnInvalidType;
        $this->object                 = $object;
        $this->objectForMap           = $objectForMap;
        $this->dateTime               = $dateTime;
    }

    /**
     * Decodes a string into PHP data.
     *
     * @param string $data    Data to decode
     * @param string $format  Format name
     * @param array  $context options that decoders have access to
     *
     * The format parameter specifies which format the data is in; valid values
     * depend on the specific implementation. The only format we support is 'yaml'
     *
     * @return mixed
     *
     * @throws UnexpectedValueException
     */
    public function decode($data, $format, array $context = [])
    {
        $context = $this->resolveContext($context);

        if ($this->isYamlOldStyleInterface()) {
            $results = Yaml::parse(
                $data,
                $context[ self::OPTION_EXCEPTION_ON_INVALID_TYPE ],
                $context[ self::OPTION_OBJECT ],
                $context[ self::OPTION_OBJECT_FOR_MAP ]
            );
        } else {
            $options = $this->contextToOptions($context);

            $results = Yaml::parse($data, $options);
        }

        return $results;
    }

    /**
     * Checks whether the deserializer can decode from given format.
     *
     * We only support yaml.
     *
     * @param string $format format name
     *
     * @return bool
     */
    public function supportsDecoding($format)
    {
        return $format == self::SUPPORTED_ENCODING_YAML;
    }

    /**
     * Merges the default options of the Yaml Decoder with the passed context.
     *
     * @param array $context
     *
     * @return array
     */
    private function resolveContext(array $context)
    {
        $defaultOptions = [
            self::OPTION_EXCEPTION_ON_INVALID_TYPE => $this->exceptionOnInvalidType,
            self::OPTION_OBJECT                    => $this->object,
            self::OPTION_OBJECT_FOR_MAP            => $this->objectForMap,
            self::OPTION_DATE_TIME                 => $this->dateTime,
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
            self::OPTION_EXCEPTION_ON_INVALID_TYPE => Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE,
            self::OPTION_OBJECT                    => Yaml::PARSE_OBJECT,
            self::OPTION_OBJECT_FOR_MAP            => Yaml::PARSE_OBJECT_FOR_MAP,
            self::OPTION_DATE_TIME                 => Yaml::PARSE_DATETIME,
        ];

        $bitMaskedOption = 0;

        foreach ($optionToBitMap as $option => $bitMask) {
            if ($options[ $option ]) {
                $bitMaskedOption = $bitMaskedOption | $bitMask;
            }
        }

        return $bitMaskedOption;
    }

    private function isYamlOldStyleInterface()
    {
        return !defined("Symfony\\Component\\Yaml\\Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE");
    }
}
