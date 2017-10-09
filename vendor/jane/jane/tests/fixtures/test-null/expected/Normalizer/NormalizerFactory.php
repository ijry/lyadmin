<?php

namespace Joli\Jane\Tests\Expected\Normalizer;

class NormalizerFactory
{
    public static function create()
    {
        $normalizers   = [];
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ReferenceNormalizer();
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ArrayDenormalizer();
        $normalizers[] = new TestNormalizer();

        return $normalizers;
    }
}
