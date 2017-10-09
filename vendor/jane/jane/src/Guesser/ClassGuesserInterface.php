<?php

namespace Joli\Jane\Guesser;

interface ClassGuesserInterface
{
    /**
     * Guess model
     *
     * This guesser should create a Model and the associated File
     * The file must be inject into the context
     *
     * @param mixed $object
     * @param string $name
     *
     * @return \Joli\Jane\Guesser\Guess\ClassGuess[] An array of class, key represent the hash of object mapped, and the value is the information about the class to be generated
     */
    public function guessClass($object, $name);
}
