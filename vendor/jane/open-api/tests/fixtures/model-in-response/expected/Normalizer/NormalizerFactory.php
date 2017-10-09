<?php

namespace Joli\Jane\OpenApi\Tests\Expected\Normalizer;

class NormalizerFactory
{
    public static function create()
    {
        $normalizers   = [];
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ArrayDenormalizer();
        $normalizers[] = new SchemaNormalizer();
        $normalizers[] = new ObjectPropertyNormalizer();
        $normalizers[] = new ErrorNormalizer();

        return $normalizers;
    }
}
