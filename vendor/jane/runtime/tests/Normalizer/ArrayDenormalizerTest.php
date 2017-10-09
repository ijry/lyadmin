<?php

namespace Joli\Jane\Runtime\Tests\Normalizer;

use Joli\Jane\Runtime\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArrayDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayDenormalizer */
    private $arrayDenormalizer;

    public function setUp()
    {
        $this->arrayDenormalizer = new ArrayDenormalizer();
    }

    public function testSupportDenormalization()
    {
        $this->arrayDenormalizer->setSerializer(new FooSerializer());
        $this->assertTrue($this->arrayDenormalizer->supportsDenormalization([], 'Foo[]'));
    }

    public function testNoSerializer()
    {
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization([], 'Foo[]'));
    }

    public function testUnderlyingClassCannotBeDenormalized()
    {
        $this->arrayDenormalizer->setSerializer(new FooSerializer());
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization([], 'Bar[]'));
    }

    public function testNoArray()
    {
        $this->arrayDenormalizer->setSerializer(new FooSerializer());
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization('data', 'Foo[]'));
    }

    public function testInvalidType()
    {
        $this->arrayDenormalizer->setSerializer(new FooSerializer());
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization('data', 'Foo'));
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization('data', '[]Foo'));
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization('data', 'Foo[]Foo'));
        $this->assertFalse($this->arrayDenormalizer->supportsDenormalization('data', 'Fo[]o'));
    }

    public function testDenormalize()
    {
        $this->arrayDenormalizer->setSerializer(new FooSerializer());
        $denormalizedData = $this->arrayDenormalizer->denormalize([1, 2, 3, 4, 5], 'Foo[]');

        $this->assertInternalType('array', $denormalizedData);
        $this->assertCount(5, $denormalizedData);
        $this->assertInstanceOf(Foo::class, $denormalizedData[0]);
        $this->assertInstanceOf(Foo::class, $denormalizedData[1]);
        $this->assertInstanceOf(Foo::class, $denormalizedData[2]);
        $this->assertInstanceOf(Foo::class, $denormalizedData[3]);
        $this->assertInstanceOf(Foo::class, $denormalizedData[4]);
    }
}

class FooSerializer implements SerializerInterface, DenormalizerInterface
{
    public function serialize($data, $format, array $context = [])
    {
        return null;
    }

    public function deserialize($data, $type, $format, array $context = [])
    {
        return null;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return new Foo();
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
       return $type === 'Foo';
    }
}

class Foo
{
}
