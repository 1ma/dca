<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\DTO;

final class QueryPrivate
{
    private array $headers;
    private array $body;

    public function __construct(array $headers, array $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}
