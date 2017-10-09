<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 2017/9/30
 * Use PhantomJS to crawl Javascript dynamically rendered pages.
 */

namespace QL\Ext;


use JonnyW\PhantomJs\Http\RequestInterface;
use QL\Contracts\PluginContract;
use QL\QueryList;
use JonnyW\PhantomJs\Client;
use Closure;

class PhantomJs implements PluginContract
{
    protected static $browser = null;

    public static function install(QueryList $queryList, ...$opt)
    {
        // PhantomJS bin path
        $phantomJsBin = $opt[0];
        $name = $opt[1] ?? 'browser';
        $queryList->bind($name,function ($request,$debug = false,$commandOpt = []) use($phantomJsBin){
            return PhantomJs::render($this,$phantomJsBin,$request,$debug,$commandOpt);
        });
        
    }

    public static function render(QueryList $queryList,$phantomJsBin,$url,$debug = false,$commandOpt = [])
    {
        $client = self::getBrowser($phantomJsBin,$commandOpt);
        $request = $client->getMessageFactory()->createRequest();
        if($url instanceof Closure){
            $request = $url($request);
        }else{
            $request->setMethod('GET');
            $request->setUrl($url);
        }
        $response = $client->getMessageFactory()->createResponse();
        if($debug) {
            $client->getEngine()->debug(true);
        }
        $client->send($request, $response);
        if($debug){
            print_r($client->getLog());
            print_r($response->getConsole());
        }
        $html = '<html>'.$response->getContent().'</html>';
        $queryList->setHtml($html);
        return $queryList;
    }

    protected static function getBrowser($phantomJsBin,$commandOpt = [])
    {
        $defaultOpt = [
           '--load-images' => 'false',
           '--ignore-ssl-errors'  => 'true'
        ];
        $commandOpt = array_merge($defaultOpt,$commandOpt);
        
        if(self::$browser == null){
            self::$browser = Client::getInstance();
            self::$browser->getEngine()->setPath($phantomJsBin);
        }
        foreach ($commandOpt as $k => $v) {
            $str = sprintf('%s=%s',$k,$v);
            self::$browser->getEngine()->addOption($str);
        }
        return self::$browser;
    }

}