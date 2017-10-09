<?php

namespace spec\Fitbug\SymfonySerializer\YamlEncoderDecoder;

use PhpSpec\ObjectBehavior;

class YamlEncodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Fitbug\SymfonySerializer\YamlEncoderDecoder\YamlEncode');

    }

    function it_is_a_encoder()
    {
        $this->shouldImplement('Symfony\Component\Serializer\Encoder\EncoderInterface');
    }

    function it_supports_type_yaml()
    {
        $this->supportsEncoding('yaml')->shouldReturn(true);
    }

    function it_does_not_support_other_formats()
    {
        $this->supportsEncoding('json')->shouldReturn(false);
    }

    function it_encodes_yaml()
    {
        $basicYaml
            = <<<YAML
example: yaml

YAML;

        $this->encode(['example' => 'yaml'], 'yaml')->shouldReturn($basicYaml);
    }

    function it_encodes_using_passes_options_in_constructor_to_parser()
    {
        $basicYaml
            = <<<YAML
example:
    yaml: example

YAML;

        $this->beConstructedWith(
            false,
            false,
            false,
            false,
            2,
            4
        );

        $this->encode(['example' => ['yaml' => 'example']], 'yaml')->shouldReturn($basicYaml);
    }

    function it_passes_options_in_the_contect_to_the_parser_and_overrides_defaults()
    {
        $basicYaml
            = <<<YAML
example:
  yaml: example

YAML;

        $this->beConstructedWith(
            false,
            false,
            false,
            false,
            2,
            4
        );

        $this->encode(
            ['example' => ['yaml' => 'example']], 'yaml', ['yaml_encode_indent' => 2]
        )->shouldReturn(
            $basicYaml
        );
    }
}
