<?php

namespace liesauer\QLPlugin;

use QL\Contracts\PluginContract;
use QL\Ext\AbsoluteUrl;
use QL\QueryList;

class SimpleForm implements PluginContract
{
    public static function install(QueryList $querylist, ...$opts)
    {
        $querylist->use(AbsoluteUrl::class);
        $querylist->bind('simpleForm', function ($formUrl, $formSelector = '', $formParams = [], $postParams = [], ...$args) {
            $formParams            = is_array($formParams) ? $formParams : [];
            $formParams['method']  = isset($formParams['method']) ? strtolower($formParams['method']) : 'get';
            $formParams['params']  = isset($formParams['params']) && is_array($formParams['params']) ? $formParams['params'] : [];
            $formParams['options'] = isset($formParams['options']) && is_array($formParams['options']) ? $formParams['options'] : [];

            $postParams            = is_array($postParams) ? $postParams : [];
            $postParams['method']  = isset($postParams['method']) ? strtolower($postParams['method']) : 'post';
            $postParams['params']  = isset($postParams['params']) && is_array($postParams['params']) ? $postParams['params'] : [];
            $postParams['options'] = isset($postParams['options']) && is_array($postParams['options']) ? $postParams['options'] : [];

            $formSelector = !is_string($formSelector) || empty($formSelector) ? 'form' : $formSelector;

            $formMethod = $formParams['method'];
            $postMethod = $postParams['method'];

            if (($formMethod !== 'get' && $formMethod !== 'post') || ($postMethod !== 'get' && $postMethod !== 'post')) {
                throw new \Exception('only method [get|post] supported.');
            }

            $form = $this->$formMethod($formUrl, $formParams['params'], $formParams['options'])->find($formSelector);
            // $inputs    = $form->find('input[name]');
            $action    = $form->attr('action');
            $formDatas = $form->serializeArray();
            $postDatas = [];
            foreach ($formDatas as $formData) {
                if (isset($postParams['params'][$formData['name']])) {
                    $postDatas[$formData['name']] = $postParams['params'][$formData['name']];
                } else {
                    $postDatas[$formData['name']] = $formData['value'];
                }
            }
            $html = $this->$postMethod($this->absoluteUrlHelper($formUrl, $action), $postDatas, $postParams['options'])->getHtml();

            return $this->html($html);
        });
    }
}
