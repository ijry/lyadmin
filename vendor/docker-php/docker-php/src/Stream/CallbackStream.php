<?php

namespace Docker\Stream;

use Psr\Http\Message\StreamInterface;

abstract class CallbackStream
{
    protected $stream;

    private $onNewFrameCallables = [];

    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Called when there is a new frame from the stream
     *
     * @param callable $onNewFrame
     */
    public function onFrame(callable $onNewFrame)
    {
        $this->onNewFrameCallables[] = $onNewFrame;
    }

    /**
     * Read a frame in the stream
     *
     * @return mixed
     */
    abstract protected function readFrame();

    /**
     * Wait for stream to finish and call callables if defined
     */
    public function wait()
    {
        while (!$this->stream->eof()) {
            $frame = $this->readFrame();

            if ($frame !== null) {
                if (!is_array($frame)) {
                    $frame = [$frame];
                }

                foreach ($this->onNewFrameCallables as $newFrameCallable) {
                    call_user_func_array($newFrameCallable, $frame);
                }
            }
        }
    }

    public function closeAndRead()
    {
        $this->stream->close();
        $this->wait();
    }
}
