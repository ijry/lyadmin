<?php

namespace Joli\Jane\OpenApi\Model;

class Info
{
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $version;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $termsOfService;
    /**
     * @var Contact
     */
    protected $contact;
    /**
     * @var License
     */
    protected $license;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return self
     */
    public function setVersion($version = null)
    {
        $this->version = $version;

        return $this;
    }

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
     * @return string
     */
    public function getTermsOfService()
    {
        return $this->termsOfService;
    }

    /**
     * @param string $termsOfService
     *
     * @return self
     */
    public function setTermsOfService($termsOfService = null)
    {
        $this->termsOfService = $termsOfService;

        return $this;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     *
     * @return self
     */
    public function setContact(Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return License
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param License $license
     *
     * @return self
     */
    public function setLicense(License $license = null)
    {
        $this->license = $license;

        return $this;
    }
}
