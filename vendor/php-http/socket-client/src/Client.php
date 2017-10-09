<?php

namespace Http\Client\Socket;

use Http\Client\HttpClient;
use Http\Client\Socket\Exception\ConnectionException;
use Http\Client\Socket\Exception\InvalidRequestException;
use Http\Client\Socket\Exception\SSLConnectionException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\ResponseFactory;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Socket Http Client.
 *
 * Use stream and socket capabilities of the core of PHP to send HTTP requests
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class Client implements HttpClient
{
    use RequestWriter;
    use ResponseReader;

    private $config = [
        'remote_socket' => null,
        'timeout' => null,
        'stream_context_options' => [],
        'stream_context_param' => [],
        'ssl' => null,
        'write_buffer_size' => 8192,
        'ssl_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT,
    ];

    /**
     * Constructor.
     *
     * @param ResponseFactory $responseFactory Response factory for creating response
     * @param array           $config          {
     *
     *    @var string $remote_socket          Remote entrypoint (can be a tcp or unix domain address)
     *    @var int    $timeout                Timeout before canceling request
     *    @var array  $stream_context_options Context options as defined in the PHP documentation
     *    @var array  $stream_context_param   Context params as defined in the PHP documentation
     *    @var bool   $ssl                    Use ssl, default to scheme from request, false if not present
     *    @var int    $write_buffer_size      Buffer when writing the request body, defaults to 8192
     *    @var int    $ssl_method             Crypto method for ssl/tls, see PHP doc, defaults to STREAM_CRYPTO_METHOD_TLS_CLIENT
     * }
     */
    public function __construct(ResponseFactory $responseFactory = null, array $config = [])
    {
        if (null === $responseFactory) {
            $responseFactory = MessageFactoryDiscovery::find();
        }

        $this->responseFactory = $responseFactory;
        $this->config = $this->configure($config);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request)
    {
        $remote = $this->config['remote_socket'];
        $useSsl = $this->config['ssl'];

        if (!$request->hasHeader('Connection')) {
            $request = $request->withHeader('Connection', 'close');
        }

        if (null === $remote) {
            $remote = $this->determineRemoteFromRequest($request);
        }

        if (null === $useSsl) {
            $useSsl = ($request->getUri()->getScheme() === 'https');
        }

        $socket = $this->createSocket($request, $remote, $useSsl);

        try {
            $this->writeRequest($socket, $request, $this->config['write_buffer_size']);
            $response = $this->readResponse($request, $socket);
        } catch (\Exception $e) {
            $this->closeSocket($socket);

            throw $e;
        }

        return $response;
    }

    /**
     * Create the socket to write request and read response on it.
     *
     * @param RequestInterface $request Request for
     * @param string           $remote  Entrypoint for the connection
     * @param bool             $useSsl  Whether to use ssl or not
     *
     * @throws ConnectionException|SSLConnectionException When the connection fail
     *
     * @return resource Socket resource
     */
    protected function createSocket(RequestInterface $request, $remote, $useSsl)
    {
        $errNo = null;
        $errMsg = null;
        $socket = @stream_socket_client($remote, $errNo, $errMsg, floor($this->config['timeout'] / 1000), STREAM_CLIENT_CONNECT, $this->config['stream_context']);

        if (false === $socket) {
            throw new ConnectionException($errMsg, $request);
        }

        stream_set_timeout($socket, floor($this->config['timeout'] / 1000), $this->config['timeout'] % 1000);

        if ($useSsl && false === @stream_socket_enable_crypto($socket, true, $this->config['ssl_method'])) {
            throw new SSLConnectionException(sprintf('Cannot enable tls: %s', error_get_last()['message']), $request);
        }

        return $socket;
    }

    /**
     * Close the socket, used when having an error.
     *
     * @param resource $socket
     */
    protected function closeSocket($socket)
    {
        fclose($socket);
    }

    /**
     * Return configuration for the socket client.
     *
     * @param array $config Configuration from user
     *
     * @return array Configuration resolved
     */
    protected function configure(array $config = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->config);
        $resolver->setDefault('stream_context', function (Options $options) {
            return stream_context_create($options['stream_context_options'], $options['stream_context_param']);
        });

        $resolver->setDefault('timeout', ini_get('default_socket_timeout') * 1000);

        $resolver->setAllowedTypes('stream_context_options', 'array');
        $resolver->setAllowedTypes('stream_context_param', 'array');
        $resolver->setAllowedTypes('stream_context', 'resource');
        $resolver->setAllowedTypes('ssl', ['bool', 'null']);

        return $resolver->resolve($config);
    }

    /**
     * Return remote socket from the request.
     *
     * @param RequestInterface $request
     *
     * @throws InvalidRequestException When no remote can be determined from the request
     *
     * @return string
     */
    private function determineRemoteFromRequest(RequestInterface $request)
    {
        if (!$request->hasHeader('Host') && $request->getUri()->getHost() === '') {
            throw new InvalidRequestException('Remote is not defined and we cannot determine a connection endpoint for this request (no Host header)', $request);
        }

        $host = $request->getUri()->getHost();
        $port = $request->getUri()->getPort() ?: ($request->getUri()->getScheme() === 'https' ? 443 : 80);
        $endpoint = sprintf('%s:%s', $host, $port);

        // If use the host header if present for the endpoint
        if (empty($host) && $request->hasHeader('Host')) {
            $endpoint = $request->getHeaderLine('Host');
        }

        return sprintf('tcp://%s', $endpoint);
    }
}
