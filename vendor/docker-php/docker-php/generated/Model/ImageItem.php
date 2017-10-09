<?php

namespace Docker\API\Model;

class ImageItem
{
    /**
     * @var string[]|null
     */
    protected $repoTags;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $parentId;
    /**
     * @var int
     */
    protected $created;
    /**
     * @var int
     */
    protected $size;
    /**
     * @var int
     */
    protected $virtualSize;
    /**
     * @var string[]|null
     */
    protected $labels;
    /**
     * @var string[]|null
     */
    protected $repoDigests;

    /**
     * @return string[]|null
     */
    public function getRepoTags()
    {
        return $this->repoTags;
    }

    /**
     * @param string[]|null $repoTags
     *
     * @return self
     */
    public function setRepoTags($repoTags = null)
    {
        $this->repoTags = $repoTags;

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
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     *
     * @return self
     */
    public function setParentId($parentId = null)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param int $created
     *
     * @return self
     */
    public function setCreated($created = null)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return self
     */
    public function setSize($size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getVirtualSize()
    {
        return $this->virtualSize;
    }

    /**
     * @param int $virtualSize
     *
     * @return self
     */
    public function setVirtualSize($virtualSize = null)
    {
        $this->virtualSize = $virtualSize;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param string[]|null $labels
     *
     * @return self
     */
    public function setLabels($labels = null)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getRepoDigests()
    {
        return $this->repoDigests;
    }

    /**
     * @param string[]|null $repoDigests
     *
     * @return self
     */
    public function setRepoDigests($repoDigests = null)
    {
        $this->repoDigests = $repoDigests;

        return $this;
    }
}
