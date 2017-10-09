<?php

namespace Joli\Jane\Guesser;

interface PropertiesGuesserInterface
{
    /**
     * Return all properties guessed
     *
     * @param mixed                                 $object
     * @param string                                $name
     * @param \Joli\Jane\Guesser\Guess\ClassGuess[] $classes
     *
     * @return \Joli\Jane\Guesser\Guess\Property[]
     */
    public function guessProperties($object, $name, $classes);
} 
