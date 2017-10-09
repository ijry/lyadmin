<?php

namespace Joli\Jane\Guesser;

interface GuesserInterface
{
    /**
     * Is this object supported for the guesser
     *
     * @param $object
     *
     * @return mixed
     */
    public function supportObject($object);
}
