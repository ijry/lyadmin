<?php

namespace Docker\Stream;

use GuzzleHttp\Psr7\Stream;

/**
 * This class avoid a bug in PHP where fstat return a size of 0 for process stream
 */
class TarStream extends Stream
{
    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return null;
    }
}
