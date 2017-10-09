<?php

namespace Joli\Jane\Guesser;

trait ChainGuesserAwareTrait
{
    /**
     * @var ChainGuesser
     */
    protected $chainGuesser;

    /**
     * Set the chain guesser
     *
     * @param ChainGuesser $chainGuesser
     */
    public function setChainGuesser(ChainGuesser $chainGuesser)
    {
        $this->chainGuesser = $chainGuesser;
    }
}
 