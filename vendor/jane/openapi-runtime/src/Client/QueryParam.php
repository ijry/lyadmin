<?php

namespace Joli\Jane\OpenApi\Runtime\Client;

use Symfony\Component\OptionsResolver\OptionsResolver;

class QueryParam extends OptionsResolver
{
    /**
     * @var array
     */
    protected $formParameters = array();

    /**
     * @var array
     */
    protected $headerParameters = array();

    /**
     * Define a form parameter option.
     *
     * @param $optionNames
     *
     * @return $this
     */
    public function setFormParameters($optionNames)
    {
        foreach ((array) $optionNames as $option) {
            $this->formParameters[$option] = true;
        }

        return $this;
    }

    /**
     * Define a header parameter option.
     *
     * @param $optionNames
     *
     * @return $this
     */
    public function setHeaderParameters($optionNames)
    {
        foreach ((array) $optionNames as $option) {
            $this->headerParameters[$option] = true;
        }

        return $this;
    }

    /**
     * Build the query string.
     *
     * @param $options
     *
     * @return string
     */
    public function buildQueryString($options)
    {
        $options = $this->resolve($options);

        foreach ($this->formParameters as $key => $isFormParameter) {
            if ($isFormParameter && isset($options[$key])) {
                unset($options[$key]);
            }
        }

        foreach ($this->headerParameters as $key => $isHeaderParameter) {
            if ($isHeaderParameter && isset($options[$key])) {
                unset($options[$key]);
            }
        }

        return http_build_query($options);
    }

    /**
     * Build form data string.
     *
     * @param $options
     *
     * @return string
     */
    public function buildFormDataString($options)
    {
        $options = $this->resolve($options);
        $formOptions = [];

        foreach ($this->formParameters as $key => $isFormParameter) {
            if ($isFormParameter && isset($options[$key])) {
                $formOptions[$key] = $options[$key];
            }
        }

        return http_build_query($formOptions);
    }

    /**
     * Build headers list.
     *
     * @param $options
     *
     * @return array
     */
    public function buildHeaders($options)
    {
        $options = $this->resolve($options);
        $headerOptions = [];

        foreach ($this->headerParameters as $key => $isHeaderParameter) {
            if ($isHeaderParameter && isset($options[$key])) {
                $headerOptions[$key] = $options[$key];
            }
        }

        return $headerOptions;
    }
}
