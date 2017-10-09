<?php

namespace Joli\Jane\Tests\Expected\Model;

class Test
{
    /**
     * @var \DateTime
     */
    protected $date;
    /**
     * @var \DateTime|null
     */
    protected $dateOrNull;
    /**
     * @var \DateTime|null|int
     */
    protected $dateOrNullOrInt;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return self
     */
    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateOrNull()
    {
        return $this->dateOrNull;
    }

    /**
     * @param \DateTime|null $dateOrNull
     *
     * @return self
     */
    public function setDateOrNull(\DateTime $dateOrNull = null)
    {
        $this->dateOrNull = $dateOrNull;

        return $this;
    }

    /**
     * @return \DateTime|null|int
     */
    public function getDateOrNullOrInt()
    {
        return $this->dateOrNullOrInt;
    }

    /**
     * @param \DateTime|null|int $dateOrNullOrInt
     *
     * @return self
     */
    public function setDateOrNullOrInt($dateOrNullOrInt = null)
    {
        $this->dateOrNullOrInt = $dateOrNullOrInt;

        return $this;
    }
}
