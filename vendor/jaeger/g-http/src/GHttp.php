<?php

/**
 * 基于GuzzleHttp的简单版Http客户端。 Simple Http client base on GuzzleHttp
 *
 * @Author: Jaeger <JaegerCode@gmail.com>
 *
 * @Version V1.0
 */

namespace Jaeger;

use GuzzleHttp\Client;

class GHttp
{
    private static $client = null;

    public static function getClient(array $config = [])
    {
        if(self::$client == null){
            self::$client = new Client($config);
        }
        return self::$client;
    }

    /**
     * @param $url
     * @param array $args
     * @param array $otherArgs
     * @return string
     */
    public static function get($url,$args = null,$otherArgs = [])
    {
        is_string($args) && parse_str($args,$args);
        $args = array_merge([
            'query' => $args,
            'headers' => [
                'referer' => $url,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
            ]
        ],$otherArgs);
        $client = self::getClient();
        $response = $client->request('GET', $url,$args);
        return (string)$response->getBody();
    }

    public static function getJson($url, $args = null, $otherArgs = [])
    {
        $data = self::get($url, $args , $otherArgs);
        return json_decode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $url
     * @param array $args
     * @param array $otherArgs
     * @return string
     */
    public static function post($url,$args = null,$otherArgs = [])
    {
        is_string($args) && parse_str($args,$args);
        $args = array_merge([
            'form_params' => $args,
            'headers' => [
                'referer' => $url,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
            ]
        ],$otherArgs);
        $client = self::getClient();
        $response = $client->request('Post', $url,$args);
        return (string)$response->getBody();
    }

    /**
     * @param $url
     * @param $filePath
     * @param null $args
     * @param array $otherArgs
     * @return string
     */
    public static function download($url,$filePath,$args = null,$otherArgs = [])
    {
        $otherArgs = array_merge($otherArgs,[
            'sink' => $filePath,
        ]);
        return self::get($url,$args,$otherArgs);
    }
}