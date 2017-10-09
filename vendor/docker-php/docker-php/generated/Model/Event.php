<?php

namespace Docker\API\Model;

class Event
{
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $from;
    /**
     * @var int
     */
    protected $time;
    /**
     * @var int
     */
    protected $timeNano;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return self
     */
    public function setFrom($from = null)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param int $time
     *
     * @return self
     */
    public function setTime($time = null)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeNano()
    {
        return $this->timeNano;
    }

    /**
     * @param int $timeNano
     *
     * @return self
     */
    public function setTimeNano($timeNano = null)
    {
        $this->timeNano = $timeNano;

        return $this;
    }
}
