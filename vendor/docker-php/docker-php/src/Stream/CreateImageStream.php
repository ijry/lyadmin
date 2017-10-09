<?php

namespace Docker\Stream;

/**
 * Represent a stream when pull or importing an image (with the create api endpoint of image)
 *
 * Callable(s) passed to this stream will take a CreateImageInfo object as the first argument
 */
class CreateImageStream extends MultiJsonStream
{
    /**
     * [@inheritdoc}
     */
    protected function getDecodeClass()
    {
        return 'Docker\API\Model\CreateImageInfo';
    }
}
