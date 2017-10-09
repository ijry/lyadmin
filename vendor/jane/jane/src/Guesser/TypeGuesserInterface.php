<?php

namespace Joli\Jane\Guesser;

interface TypeGuesserInterface
{
    /**
     * Return all types guessed
     *
     * @param mixed                                 $object
     * @param string                                $name
     * @param \Joli\Jane\Guesser\Guess\ClassGuess[] $classes
     *
     * @return \Joli\Jane\Guesser\Guess\Type
     */
    public function guessType($object, $name, $classes);
}
 