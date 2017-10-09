<?php

namespace PhpOffice\Common\Adapter\Zip;

use ZipArchive;

class ZipArchiveAdapter implements ZipInterface
{
    /**
     * @var ZipArchive
     */
    protected $oZipArchive;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @param string $filename
     * @throws \Exception Could not open $this->filename for writing.
     * @return mixed
     */
    public function open($filename)
    {
        $this->filename = $filename;
        $this->oZipArchive = new ZipArchive();

        if ($this->oZipArchive->open($this->filename, ZipArchive::OVERWRITE) === true) {
            return $this;
        }
        if ($this->oZipArchive->open($this->filename, ZipArchive::CREATE) === true) {
            return $this;
        }
        throw new \Exception("Could not open $this->filename for writing.");
    }

    /**
     * @return $this
     * @throws \Exception Could not close zip file $this->filename.
     */
    public function close()
    {
        if ($this->oZipArchive->close() === false) {
            throw new \Exception("Could not close zip file $this->filename.");
        }
        return $this;
    }

    /**
     * @param $localname
     * @param $contents
     * @return bool
     */
    public function addFromString($localname, $contents)
    {
        return $this->oZipArchive->addFromString($localname, $contents);
    }
}
