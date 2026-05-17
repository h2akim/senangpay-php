<?php

declare(strict_types=1);

namespace SenangPay\Tests\Support;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Http\Client\Common\HttpMethodsClientInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class FakeHttpMethodsClient implements HttpMethodsClientInterface
{
    /** @var array<int, array{method: string, uri: UriInterface, headers: array, body: mixed}> */
    private array $calls = [];

    /** @var array<int, ResponseInterface> */
    private array $responses = [];

    public function appendResponse(ResponseInterface $response): self
    {
        $this->responses[] = $response;
        return $this;
    }

    public function lastCall(): array
    {
        if (empty($this->calls)) {
            throw new \RuntimeException('No calls recorded.');
        }

        return $this->calls[array_key_last($this->calls)];
    }

    public function allCalls(): array
    {
        return $this->calls;
    }

    public function callCount(): int
    {
        return count($this->calls);
    }

    public function send(string $method, $uri, array $headers = [], $body = null): ResponseInterface
    {
        $uri = $uri instanceof UriInterface ? $uri : new Uri((string) $uri);
        $this->calls[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'headers' => $headers,
            'body' => $body,
        ];

        $response = ! empty($this->responses)
            ? array_shift($this->responses)
            : new Response(200, ['Content-Type' => 'application/json'], '{}');

        return $response;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return new Response(200, ['Content-Type' => 'application/json'], '{}');
    }

    public function get($uri, array $headers = []): ResponseInterface
    {
        return $this->send('GET', $uri, $headers);
    }

    public function head($uri, array $headers = []): ResponseInterface
    {
        return $this->send('HEAD', $uri, $headers);
    }

    public function trace($uri, array $headers = []): ResponseInterface
    {
        return $this->send('TRACE', $uri, $headers);
    }

    public function post($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('POST', $uri, $headers, $body);
    }

    public function put($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('PUT', $uri, $headers, $body);
    }

    public function patch($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('PATCH', $uri, $headers, $body);
    }

    public function delete($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('DELETE', $uri, $headers, $body);
    }

    public function options($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('OPTIONS', $uri, $headers, $body);
    }
}
