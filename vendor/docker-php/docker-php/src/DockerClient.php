<?php

namespace Docker;

use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\DecoderPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Client\Socket\Client as SocketHttpClient;
use Psr\Http\Message\RequestInterface;

class DockerClient implements HttpClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    public function __construct($socketClientOptions = [])
    {
        $messageFactory = new GuzzleMessageFactory();
        $socketClient = new SocketHttpClient($messageFactory, $socketClientOptions);
        $lengthPlugin = new ContentLengthPlugin();
        $decodingPlugin = new DecoderPlugin();
        $errorPlugin = new ErrorPlugin();

        $this->httpClient = new PluginClient($socketClient, [
            $errorPlugin,
            $lengthPlugin,
            $decodingPlugin
        ]);
    }

    /**
     * (@inheritdoc}
     */
    public function sendRequest(RequestInterface $request)
    {
        return $this->httpClient->sendRequest($request);
    }

    /**
     * @return DockerClient
     */
    public static function create()
    {
        return new self([
            'remote_socket' => 'unix:///var/run/docker.sock'
        ]);
    }

    /**
     * Create a docker client from environment variables
     *
     * @return DockerClient
     *
     * @throws \RuntimeException Throw exception when invalid environment variables are given
     */
    public static function createFromEnv()
    {
        $options = [
            'remote_socket' => getenv('DOCKER_HOST') ? getenv('DOCKER_HOST') : 'unix:///var/run/docker.sock'
        ];

        if (getenv('DOCKER_TLS_VERIFY') && getenv('DOCKER_TLS_VERIFY') == 1) {
            if (!getenv('DOCKER_CERT_PATH')) {
                throw new \RuntimeException('Connection to docker has been set to use TLS, but no PATH is defined for certificate in DOCKER_CERT_PATH docker environnement variable');
            }

            $cafile = getenv('DOCKER_CERT_PATH').DIRECTORY_SEPARATOR.'ca.pem';
            $certfile = getenv('DOCKER_CERT_PATH').DIRECTORY_SEPARATOR.'cert.pem';
            $keyfile = getenv('DOCKER_CERT_PATH').DIRECTORY_SEPARATOR.'key.pem';

            $stream_context = [
                'cafile'        => $cafile,
                'local_cert'    => $certfile,
                'local_pk'      => $keyfile,
            ];

            if (getenv('DOCKER_PEER_NAME')) {
                $stream_context['peer_name'] = getenv('DOCKER_PEER_NAME');
            }

            $options['ssl'] = true;
            $options['stream_context_options'] = [
                'ssl' =>  $stream_context
            ];
        }

        return new self($options);
    }
}
