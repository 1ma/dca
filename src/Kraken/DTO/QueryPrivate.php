<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\DTO;

/**
 * Query
 */
class QueryPrivate
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $body;

    public function __construct(array $headers, array $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }
}