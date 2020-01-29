<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Base class most APIs are inheriting
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class AbstractApi
{
    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     *
     * @param HttpClientInterface $httpClient
     * @param Configuration $configuration
     */
    public function __construct(HttpClientInterface $httpClient, Configuration $configuration)
    {
        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers iterable|string[]|string[][] - headers names provided as keys or as part of values
     * @param string $body array|string|resource|\Traversable|\Closure
     */
    public function request(string $method, string $url, $headers = [], $body = ''): ResultPromise
    {
        $response = $this->httpClient->request($method, $url, [
            'headers' => $headers,
            'body' => $body,
        ]);

        return ResultPromise::create($response, GenericHttpResult::class);
    }
}