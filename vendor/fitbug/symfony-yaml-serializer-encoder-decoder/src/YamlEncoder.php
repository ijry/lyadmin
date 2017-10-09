<?php


namespace Fitbug\SymfonySerializer\YamlEncoderDecoder;


use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class YamlEncoder implements EncoderInterface, DecoderInterface
{
    /**
     * @var YamlEncode
     */
    private $yamlEncode;
    /**
     * @var YamlDecode
     */
    private $yamlDecode;

    /**
     * YamlEncoder constructor.
     *
     * @param YamlEncode $yamlEncodeImpl
     * @param YamlDecode $yamlDecodeImpl
     */
    public function __construct(YamlEncode $yamlEncodeImpl = null, YamlDecode $yamlDecodeImpl = null)
    {
        $this->yamlEncode = $yamlEncodeImpl ?: new YamlEncode();
        $this->yamlDecode = $yamlDecodeImpl ?: new YamlDecode();
    }


    /**
     * Decodes a string into PHP data.
     *
     * @param string $data    Data to decode
     * @param string $format  Format name
     * @param array  $context options that decoders have access to
     *
     * The format parameter specifies which format the data is in; valid values
     * depend on the specific implementation. This interface only supports YAML
     *
     * @return mixed
     *
     * @throws UnexpectedValueException
     */
    public function decode($data, $format, array $context = [])
    {
        return $this->yamlDecode->decode($data, $format, $context);
    }

    /**
     * Checks whether the deserializer can decode from given format.
     *
     * @param string $format format name
     *
     * @return bool
     */
    public function supportsDecoding($format)
    {
        return $this->yamlDecode->supportsDecoding($format);
    }

    /**
     * Encodes data into the given format.
     *
     * @param mixed  $data    Data to encode
     * @param string $format  Format name
     * @param array  $context options that normalizers/encoders have access to
     *
     * @return scalar
     *
     * @throws UnexpectedValueException
     */
    public function encode($data, $format, array $context = [])
    {
        return $this->yamlEncode->encode($data, $format, $context);
    }

    /**
     * Checks whether the serializer can encode to given format.
     *
     * @param string $format format name
     *
     * @return bool
     */
    public function supportsEncoding($format)
    {
        return $this->yamlEncode->supportsEncoding($format);
    }
}