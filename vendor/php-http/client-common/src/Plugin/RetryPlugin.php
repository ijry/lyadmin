<?php

namespace Http\Client\Common\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Retry the request if an exception is thrown.
 *
 * By default will retry only one time.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RetryPlugin implements Plugin
{
    /**
     * Number of retry before sending an exception.
     *
     * @var int
     */
    private $retry;

    /**
     * Store the retry counter for each request.
     *
     * @var array
     */
    private $retryStorage = [];

    /**
     * @param array $config {
     *
     *     @var int $retries Number of retries to attempt if an exception occurs before letting the exception bubble up.
     * }
     */
    public function __construct(array $config = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'retries' => 1,
        ]);
        $resolver->setAllowedTypes('retries', 'int');
        $options = $resolver->resolve($config);

        $this->retry = $options['retries'];
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $chainIdentifier = spl_object_hash((object) $first);

        return $next($request)->then(function (ResponseInterface $response) use ($request, $chainIdentifier) {
            if (array_key_exists($chainIdentifier, $this->retryStorage)) {
                unset($this->retryStorage[$chainIdentifier]);
            }

            return $response;
        }, function (Exception $exception) use ($request, $next, $first, $chainIdentifier) {
            if (!array_key_exists($chainIdentifier, $this->retryStorage)) {
                $this->retryStorage[$chainIdentifier] = 0;
            }

            if ($this->retryStorage[$chainIdentifier] >= $this->retry) {
                unset($this->retryStorage[$chainIdentifier]);

                throw $exception;
            }

            ++$this->retryStorage[$chainIdentifier];

            // Retry in synchrone
            $promise = $this->handleRequest($request, $next, $first);

            return $promise->wait();
        });
    }
}
