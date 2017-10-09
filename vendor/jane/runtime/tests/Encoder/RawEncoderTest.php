<?php

namespace Joli\Jane\Runtime\Tests\Encoder;

use Joli\Jane\Runtime\Encoder\RawEncoder;

class RawEncoderTest extends \PHPUnit_Framework_TestCase
{
    /** @var RawEncoder */
    private $encoder;

    public function setUp()
    {
        $this->encoder = new RawEncoder();
    }

    public function testEncode()
    {
        $data = mt_rand();
        $encoded = $this->encoder->encode($data, 'raw');

        $this->assertSame($data, $encoded);
    }

    public function testDecode()
    {
        $data = mt_rand();
        $encoded = $this->encoder->decode($data, 'raw');

        $this->assertSame($data, $encoded);
    }

    public function testSupportEncode()
    {
        $this->assertTrue($this->encoder->supportsEncoding('raw'));
        $this->assertFalse($this->encoder->supportsEncoding('notraw'));
    }

    public function testSupportDecode()
    {
        $this->assertTrue($this->encoder->supportsDecoding('raw'));
        $this->assertFalse($this->encoder->supportsDecoding('notraw'));
    }
}
