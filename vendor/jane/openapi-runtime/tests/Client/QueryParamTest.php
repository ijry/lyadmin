<?php

namespace Joli\Jane\OpenApi\Runtime\Tests;

use Joli\Jane\OpenApi\Runtime\Client\QueryParam;

class QueryParamTest extends \PHPUnit_Framework_TestCase
{
    /** @var QueryParam */
    private $queryParam;

    public function setUp()
    {
        $this->queryParam = new QueryParam();
        $this->queryParam->setDefaults([
            'foo' => 'foo_value',
            'bar' => 'bar_value',
            'foo_form' => 'foo_form_value',
            'bar_form' => 'bar_form_value',
            'foo_header' => 'foo_header_value',
            'bar_header' => 'bar_header_value',
        ]);

        $this->queryParam->setFormParameters([
            'foo_form',
            'bar_form',
        ]);

        $this->queryParam->setHeaderParameters([
            'foo_header',
            'bar_header'
        ]);
    }

    public function testBuildQueryString()
    {
        $this->assertEquals('foo=foo_value&bar=bar_value', $this->queryParam->buildQueryString([]));
        $this->assertEquals('foo=foo_value&bar=bar_replace', $this->queryParam->buildQueryString([
            'bar' => 'bar_replace',
            'bar_form' => 'bar_form_replace',
            'bar_header' => 'bar_header_replace',
        ]));
    }

    public function testBuildFormDataString()
    {
        $this->assertEquals('foo_form=foo_form_value&bar_form=bar_form_value', $this->queryParam->buildFormDataString([]));
        $this->assertEquals('foo_form=foo_form_value&bar_form=bar_form_replace', $this->queryParam->buildFormDataString([
            'bar' => 'bar_replace',
            'bar_form' => 'bar_form_replace',
            'bar_header' => 'bar_header_replace',
        ]));
    }

    public function testBuildHeaders()
    {
        $this->assertEquals([
            'foo_header' => 'foo_header_value',
            'bar_header' => 'bar_header_value',
        ], $this->queryParam->buildHeaders([]));

        $this->assertEquals([
            'foo_header' => 'foo_header_value',
            'bar_header' => 'bar_header_replace',
        ], $this->queryParam->buildHeaders([
            'bar' => 'bar_replace',
            'bar_form' => 'bar_form_replace',
            'bar_header' => 'bar_header_replace',
        ]));
    }
}
