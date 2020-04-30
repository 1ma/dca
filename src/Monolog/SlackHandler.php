<?php

declare(strict_types=1);

namespace UMA\DCA\Monolog;

use GuzzleHttp\Client;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * A custom SlackHandler that uses our own Guzzle client.
 */
class SlackHandler extends AbstractProcessingHandler
{
    /**
     * @var Client
     */
    private $http;

    /**
     * @var string
     */
    private $url;

    public function __construct(Client $http, string $url, int $minLogLevel)
    {
        $this->http = $http;
        $this->url = $url;

        parent::__construct($minLogLevel);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $this->http->post($this->url, [
            'json' => ['text' => $record['formatted']]
        ]);
    }
}
