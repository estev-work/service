<?php

declare(strict_types=1);

namespace Core\Http;

use Exception;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class Request implements ServerRequestInterface
{
    protected string $method;
    protected UriInterface $uri;
    protected array $headers;
    protected array $serverParams;
    protected array $queryParams;
    protected array $parsedBody;
    protected array $cookies;

    protected array $attributes = [];

    private string $protocolVersion = '1.1';
    protected StreamInterface $body;
    protected array $uploadedFiles;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = new Uri($_SERVER['REQUEST_URI']);
        $this->headers = getallheaders();
        $this->serverParams = $_SERVER;
        $this->queryParams = $_GET;
        $this->parsedBody = $_POST;
        $this->cookies = $_COOKIE;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody(): array|string|null
    {
        try {
            $contentType = $this->getHeaderLine('Content-Type');
            $body = $this->getBody()->getContents();
            return match (true) {
                str_contains($contentType, 'application/json') => $this->parseJson($body),
                str_contains($contentType, 'application/x-www-form-urlencoded') => $this->parseUrlEncoded($body),
                str_contains($contentType, 'multipart/form-data') => $_POST,
                str_contains($contentType, 'text/plain') => $body,
                default => $body ?: null,
            };
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    private function parseJson(string $body): array
    {
        $parsed = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Ошибка при декодировании JSON: ' . json_last_error_msg());
        }
        return $parsed;
    }

    private function parseUrlEncoded(string $body): array
    {
        parse_str($body, $parsed);
        return $parsed;
    }

    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    public function getBody(): StreamInterface
    {
        return Stream::create(fopen('php://input', 'r'));
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return isset($this->headers[$name]) ? implode(', ', (array)$this->headers[$name]) : '';
    }

    public function withHeader($name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers[$name] = (array)$value;
        return $clone;
    }

    public function withAddedHeader($name, $value): MessageInterface
    {
        $clone = clone $this;
        if (isset($clone->headers[$name])) {
            $clone->headers[$name] = array_merge($clone->headers[$name], (array)$value);
        } else {
            $clone->headers[$name] = (array)$value;
        }
        return $clone;
    }

    public function withoutHeader($name): MessageInterface
    {
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
    }

    public function getRequestTarget(): string
    {
        return $this->uri->getPath();
    }

    public function withRequestTarget($requestTarget): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $clone->uri->withPath($requestTarget);
        return $clone;
    }

    public function withMethod($method): RequestInterface
    {
        $clone = clone $this;
        $clone->method = strtoupper($method);
        return $clone;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;
        return $clone;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getUploadedFiles(): array
    {
        return $_FILES;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;
        return $clone;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->cookies = $cookies;
        return $clone;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->queryParams = $query;
        return $clone;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->parsedBody = $data;
        return $clone;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    public function withoutAttribute($name): ServerRequestInterface
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }

}