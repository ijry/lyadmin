<?php

namespace Joli\Jane\Guesser\JsonSchema;

use Joli\Jane\Guesser\Guess\DateTimeType;
use Joli\Jane\Guesser\GuesserInterface;
use Joli\Jane\Guesser\TypeGuesserInterface;
use Joli\Jane\Model\JsonSchema;

class DateTimeGuesser implements GuesserInterface, TypeGuesserInterface
{
    /** @var string Format of date to use */
    private $dateFormat;

    public function __construct($dateFormat = \DateTime::RFC3339)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * {@inheritDoc}
     */
    public function supportObject($object)
    {
        return (($object instanceof JsonSchema) && $object->getType() === 'string' && $object->getFormat() === 'date-time');
    }

    /**
     * {@inheritDoc}
     */
    public function guessType($object, $name, $classes)
    {
        return new DateTimeType($object, $this->dateFormat);
    }
}
