<?php

namespace Http\Client\Socket;

use Http\Client\Socket\Exception\BrokenPipeException;
use Psr\Http\Message\RequestInterface;

/**
 * Method for writing request.
 *
 * Mainly used by SocketHttpClient
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
trait RequestWriter
{
    /**
     * Write a request to a socket.
     *
     * @param resource         $socket
     * @param RequestInterface $request
     * @param int              $bufferSize
     *
     * @throws BrokenPipeException
     */
    protected function writeRequest($socket, RequestInterface $request, $bufferSize = 8192)
    {
        if (false === $this->fwrite($socket, $this->transformRequestHeadersToString($request))) {
            throw new BrokenPipeException('Failed to send request, underlying socket not accessible, (BROKEN EPIPE)', $request);
        }

        if ($request->getBody()->isReadable()) {
            $this->writeBody($socket, $request, $bufferSize);
        }
    }

    /**
     * Write Body of the request.
     *
     * @param resource         $socket
     * @param RequestInterface $request
     * @param int              $bufferSize
     *
     * @throws BrokenPipeException
     */
    protected function writeBody($socket, RequestInterface $request, $bufferSize = 8192)
    {
        $body = $request->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        while (!$body->eof()) {
            $buffer = $body->read($bufferSize);

            if (false === $this->fwrite($socket, $buffer)) {
                throw new BrokenPipeException('An error occur when writing request to client (BROKEN EPIPE)', $request);
            }
        }
    }

    /**
     * Produce the header of request as a string based on a PSR Request.
     *
     * @param RequestInterface $request
     *
     * @return string
     */
    protected function transformRequestHeadersToString(RequestInterface $request)
    {
        $message = vsprintf('%s %s HTTP/%s', [
            strtoupper($request->getMethod()),
            $request->getRequestTarget(),
            $request->getProtocolVersion(),
        ])."\r\n";

        foreach ($request->getHeaders() as $name => $values) {
            $message .= $name.': '.implode(', ', $values)."\r\n";
        }

        $message .= "\r\n";

        return $message;
    }

    /**
     * Replace fwrite behavior as api is broken in PHP.
     *
     * @see https://secure.phabricator.com/rPHU69490c53c9c2ef2002bc2dd4cecfe9a4b080b497
     *
     * @param resource $stream The stream resource
     * @param string   $bytes  Bytes written in the stream
     *
     * @return bool|int false if pipe is broken, number of bytes written otherwise
     */
    private function fwrite($stream, $bytes)
    {
        if (!strlen($bytes)) {
            return 0;
        }
        $result = @fwrite($stream, $bytes);
        if ($result !== 0) {
            // In cases where some bytes are witten (`$result > 0`) or
            // an error occurs (`$result === false`), the behavior of fwrite() is
            // correct. We can return the value as-is.
            return $result;
        }
        // If we make it here, we performed a 0-length write. Try to distinguish
        // between EAGAIN and EPIPE. To do this, we're going to `stream_select()`
        // the stream, write to it again if PHP claims that it's writable, and
        // consider the pipe broken if the write fails.
        $read = [];
        $write = [$stream];
        $except = [];
        @stream_select($read, $write, $except, 0);
        if (!$write) {
            // The stream isn't writable, so we conclude that it probably really is
            // blocked and the underlying error was EAGAIN. Return 0 to indicate that
            // no data could be written yet.
            return 0;
        }
        // If we make it here, PHP **just** claimed that this stream is writable, so
        // perform a write. If the write also fails, conclude that these failures are
        // EPIPE or some other permanent failure.
        $result = @fwrite($stream, $bytes);
        if ($result !== 0) {
            // The write worked or failed explicitly. This value is fine to return.
            return $result;
        }
        // We performed a 0-length write, were told that the stream was writable, and
        // then immediately performed another 0-length write. Conclude that the pipe
        // is broken and return `false`.
        return false;
    }
}
