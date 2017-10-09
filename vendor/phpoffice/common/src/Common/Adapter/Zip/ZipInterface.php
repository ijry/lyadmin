<?php

namespace PhpOffice\Common\Adapter\Zip;

interface ZipInterface
{
    public function open($filename);
    public function close();
    public function addFromString($localname, $contents);
}
