<?php

namespace Joli\Jane\Runtime\Tests\Normalizer;

use Joli\Jane\Runtime\Normalizer\ReferenceNormalizer;
use Joli\Jane\Runtime\Reference;

class ReferenceNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ReferenceNormalizer */
    private $referenceNormalizer;

    public function setUp()
    {
        $this->referenceNormalizer = new ReferenceNormalizer();
    }

    public function testSupports()
    {
        $this->assertFalse($this->referenceNormalizer->supportsNormalization('toto'));
        $this->assertTrue($this->referenceNormalizer->supportsNormalization(new Reference('reference', 'schema')));
    }

    /**
     * @dataProvider normalizeProvider
     */
    public function testNormalize($referenceString)
    {
        $reference = new Reference($referenceString, 'schema');
        $normalized = $this->referenceNormalizer->normalize($reference);

        $this->assertEquals($referenceString, $normalized->{'$ref'});
    }

    public function normalizeProvider()
    {
        return [
            ['#pointer'],
            ['#'],
            ['https://my-site/schema#pointer'],
            ['my-site.com/teest']
        ];
    }
}
