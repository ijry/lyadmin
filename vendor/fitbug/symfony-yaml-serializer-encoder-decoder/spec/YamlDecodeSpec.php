<?php

namespace spec\Fitbug\SymfonySerializer\YamlEncoderDecoder;

use PhpSpec\ObjectBehavior;

class YamlDecodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Fitbug\SymfonySerializer\YamlEncoderDecoder\YamlDecode');

    }

    function it_is_a_decoder()
    {
        $this->shouldImplement('Symfony\Component\Serializer\Encoder\DecoderInterface');
    }

    function it_supports_type_yaml()
    {
        $this->supportsDecoding('yaml')->shouldReturn(true);
    }

    function it_does_not_support_other_formats()
    {
        $this->supportsDecoding('json')->shouldReturn(false);
    }

    function it_decodes_yaml()
    {
        $basicYaml
            = <<<YAML
example: yaml

YAML;

        $this->decode($basicYaml, 'yaml')->shouldReturn(['example' => 'yaml']);
    }

    function it_decodes_passes_options_in_constructor_to_parser()
    {
        $basicYaml
            = <<<YAML
example: yaml

YAML;

        $this->beConstructedWith(
            false,
            true,
            true,
            true
        );

        $this->decode($basicYaml, 'yaml')->shouldHaveType('stdClass');
    }

    function it_passes_options_in_the_contect_to_the_parser_and_overrides_defaults()
    {
        $basicYaml
            = <<<YAML
example: yaml

YAML;

        $this->beConstructedWith(
            false,
            true,
            true,
            true
        );

        $this->decode(
            $basicYaml,
            'yaml',
            [
                'yaml_decode_exception_on_invalid_type' => false,
                'yaml_decode_object'                    => false,
                'yaml_decode_object_for_map'            => false,
                'yaml_decode_date_time'                 => false,
            ]
        )->shouldReturn(['example' => 'yaml']);
    }
}
