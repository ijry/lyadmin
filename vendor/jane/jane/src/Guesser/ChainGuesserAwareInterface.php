<?php

namespace Joli\Jane\Guesser;

interface ChainGuesserAwareInterface
{
    /**
     * Set the chain guesser
     *
     * @param ChainGuesser $chainGuesser
     */
    public function setChainGuesser(ChainGuesser $chainGuesser);
}
 