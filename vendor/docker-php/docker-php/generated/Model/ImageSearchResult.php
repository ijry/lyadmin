<?php

namespace Docker\API\Model;

class ImageSearchResult
{
    /**
     * @var string
     */
    protected $description;
    /**
     * @var bool
     */
    protected $isOfficial;
    /**
     * @var bool
     */
    protected $isAutomated;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $starCount;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsOfficial()
    {
        return $this->isOfficial;
    }

    /**
     * @param bool $isOfficial
     *
     * @return self
     */
    public function setIsOfficial($isOfficial = null)
    {
        $this->isOfficial = $isOfficial;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAutomated()
    {
        return $this->isAutomated;
    }

    /**
     * @param bool $isAutomated
     *
     * @return self
     */
    public function setIsAutomated($isAutomated = null)
    {
        $this->isAutomated = $isAutomated;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getStarCount()
    {
        return $this->starCount;
    }

    /**
     * @param int $starCount
     *
     * @return self
     */
    public function setStarCount($starCount = null)
    {
        $this->starCount = $starCount;

        return $this;
    }
}
