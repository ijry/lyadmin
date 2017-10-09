<?php

namespace Docker\Stream;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Represent a stream that decode a stream with multiple json in it
 */
abstract class MultiJsonStream extends CallbackStream
{
    /** @var SerializerInterface Serializer to decode incoming json object */
    private $serializer;

    public function __construct(StreamInterface $stream, SerializerInterface $serializer)
    {
        parent::__construct($stream);

        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    protected function readFrame()
    {
        $jsonFrameEnd = false;
        $lastJsonChar = '';
        $inquote      = false;
        $jsonFrame    = "";
        $level        = 0;

        while (!$jsonFrameEnd && !$this->stream->eof()) {
            $jsonChar   = $this->stream->read(1);

            if ((boolean)($jsonChar == '"' && $lastJsonChar != '\\')) {
                $inquote = !$inquote;
            }

            if (!$inquote && in_array($jsonChar, [" ", "\r", "\n", "\t"])) {
                continue;
            }

            if (!$inquote && in_array($jsonChar, ['{', '['])) {
                $level++;
            }

            if (!$inquote && in_array($jsonChar, ['}', ']'])) {
                $level--;

                if ($level == 0) {
                    $jsonFrameEnd = true;
                    $jsonFrame .= $jsonChar;

                    continue;
                }
            }

            $jsonFrame .= $jsonChar;
        }

        // Invalid last json, or timeout, or connection close before receiving
        if (!$jsonFrameEnd) {
            return null;
        }

        return $this->serializer->deserialize($jsonFrame, $this->getDecodeClass(), 'json');
    }

    /**
     * Get the decode class to pass to serializer
     *
     * @return string
     */
    abstract protected function getDecodeClass();
}
