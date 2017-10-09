<?php

namespace Docker\API\Model;

class BuildInfo
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $stream;
    /**
     * @var string
     */
    protected $error;
    /**
     * @var ErrorDetail
     */
    protected $errorDetail;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $progress;
    /**
     * @var ProgressDetail
     */
    protected $progressDetail;

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
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @param string $stream
     *
     * @return self
     */
    public function setStream($stream = null)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return self
     */
    public function setError($error = null)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return ErrorDetail
     */
    public function getErrorDetail()
    {
        return $this->errorDetail;
    }

    /**
     * @param ErrorDetail $errorDetail
     *
     * @return self
     */
    public function setErrorDetail(ErrorDetail $errorDetail = null)
    {
        $this->errorDetail = $errorDetail;

        return $this;
    }

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
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param string $progress
     *
     * @return self
     */
    public function setProgress($progress = null)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * @return ProgressDetail
     */
    public function getProgressDetail()
    {
        return $this->progressDetail;
    }

    /**
     * @param ProgressDetail $progressDetail
     *
     * @return self
     */
    public function setProgressDetail(ProgressDetail $progressDetail = null)
    {
        $this->progressDetail = $progressDetail;

        return $this;
    }
}
