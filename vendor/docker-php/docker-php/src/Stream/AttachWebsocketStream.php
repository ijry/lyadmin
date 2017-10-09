<?php

namespace Docker\Stream;
use Psr\Http\Message\StreamInterface;

/**
 * An interactive stream is used when communicating with an attached docker container
 *
 * It helps dealing with encoding and decoding frame from websocket protocol (hybi 10)
 *
 * @see https://tools.ietf.org/html/rfc6455#section-5.2
 */
class AttachWebsocketStream
{
    /** @var resource The underlying socket */
    private $socket;

    public function __construct(StreamInterface $stream)
    {
        $this->socket = $stream->detach();
    }

    /**
     * Send input to the container
     *
     * @param string $data Data to send
     */
    public function write($data)
    {
        $rand  = rand(0, 28);
        $frame = [
            'fin'      => 1,
            'rsv1'     => 0,
            'rsv2'     => 0,
            'rsv3'     => 0,
            'opcode'   => 1, // We always send text
            'mask'     => 1,
            'len'      => strlen($data),
            'mask_key' => substr(md5(uniqid()), $rand, 4),
            'data'     => $data,
        ];

        if ($frame['mask'] == 1) {
            for ($i = 0; $i < $frame['len']; $i++) {
                $frame['data']{$i}
                    = chr(ord($frame['data']{$i}) ^ ord($frame['mask_key']{$i % 4}));
            }
        }

        if ($frame['len'] > pow(2, 16)) {
            $len = 127;
        } elseif ($frame['len'] > 125) {
            $len = 126;
        } else {
            $len = $frame['len'];
        }

        $firstByte  = ($frame['fin'] << 7) | (($frame['rsv1'] << 7) >> 1) | (($frame['rsv2'] << 7) >> 2) | (($frame['rsv3'] << 7) >> 3) | (($frame['opcode'] << 4) >> 4);
        $secondByte = ($frame['mask'] << 7) | (($len << 1) >> 1);

        $this->socketWrite(chr($firstByte));
        $this->socketWrite(chr($secondByte));

        if ($len == 126) {
            $this->socketWrite(pack('n', $frame['len']));
        } elseif ($len == 127) {
            $higher = $frame['len'] >> 32;
            $lower  = ($frame['len'] << 32) >> 32;
            $this->socketWrite(pack('N', $higher));
            $this->socketWrite(pack('N', $lower));
        }

        if ($frame['mask'] == 1) {
            $this->socketWrite($frame['mask_key']);
        }

        $this->socketWrite($frame['data']);
    }

    /**
     * Block until it receive a frame from websocket or return null if no more connexion
     *
     * @param int     $waitTime      Time to wait in seconds before return false
     * @param int     $waitMicroTime Time to wait in microseconds before return false
     * @param boolean $getFrame      Whether to return the frame of websocket or only the data
     *
     * @return null|false|string|array Null for socket not available, false for no message, string for the last message and the frame array if $getFrame is set to true
     */
    public function read($waitTime = 0, $waitMicroTime = 200000, $getFrame = false)
    {
        if (!is_resource($this->socket) || feof($this->socket)) {
            return null;
        }

        $read   = [$this->socket];
        $write  = null;
        $expect = null;

        if (stream_select($read, $write, $expect, $waitTime, $waitMicroTime) == 0) {
            return false;
        }

        $firstByte  = $this->socketRead(1);
        $frame      = [];
        $firstByte  = ord($firstByte);
        $secondByte = ord($this->socketRead(1));

        // First byte decoding
        $frame['fin']    = ($firstByte & 128) >> 7;
        $frame['rsv1']   = ($firstByte & 64)  >> 6;
        $frame['rsv2']   = ($firstByte & 32)  >> 5;
        $frame['rsv3']   = ($firstByte & 16)  >> 4;
        $frame['opcode'] = ($firstByte & 15);

        // Second byte decoding
        $frame['mask'] = ($secondByte & 128) >> 7;
        $frame['len']  = ($secondByte & 127);

        // Get length of the frame
        if ($frame['len'] == 126) {
            $frame['len'] = unpack('n', $this->socketRead(2))[1];
        } elseif ($frame['len'] == 127) {
            list($higher, $lower) = array_values(unpack('N2', $this->socketRead(8)));
            $frame['len'] = ($higher << 32) | $lower;
        }

        // Get the mask key if needed
        if ($frame['mask'] == 1) {
            $frame['mask_key'] = $this->socketRead(4);
        }

        $frame['data'] = $this->socketRead($frame['len']);

        // Decode data if needed
        if ($frame['mask'] == 1) {
            for ($i = 0; $i < $frame['len']; $i++) {
                $frame['data']{$i} = chr(ord($frame['data']{$i}) ^ ord($frame['mask_key']{$i % 4}));
            }
        }

        if ($getFrame) {
            return $frame;
        }

        return (string)$frame['data'];
    }

    /**
     * Force to have something of the expected size (block)
     *
     * @param $length
     *
     * @return string
     */
    private function socketRead($length)
    {
        $read = "";

        do {
            $read .= fread($this->socket, $length - strlen($read));
        } while (strlen($read) < $length && !feof($this->socket));

        return $read;
    }

    /**
     * Write to the socket
     *
     * @param $data
     *
     * @return int
     */
    private function socketWrite($data)
    {
        return fwrite($this->socket, $data);
    }
}
