<?php

namespace Docker\API\Model;

class ImageHistoryItem
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var int
     */
    protected $created;
    /**
     * @var string
     */
    protected $createdBy;
    /**
     * @var string[]|null
     */
    protected $tags;
    /**
     * @var int
     */
    protected $size;
    /**
     * @var string
     */
    protected $comment;

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
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     *
     * @return self
     */
    public function setCreatedBy($createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string[]|null $tags
     *
     * @return self
     */
    public function setTags($tags = null)
    {
        $this->tags = $tags;

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
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return self
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }
}
