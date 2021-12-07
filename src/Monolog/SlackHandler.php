<?php

declare(strict_types=1);

namespace UMA\DCA\Monolog;

use GuzzleHttp\ClientInterface;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * A custom Monolog handler that posts logs to a Slack webhook.
 */
final class SlackHandler extends AbstractProcessingHandler
{
    private ClientInterface $http;
    private string $url;

    public function __construct(ClientInterface $http, string $url, int $minLogLevel)
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
        $this->http->request('POST', $this->url, ['json' => ['text' => $record['formatted']]]);
    }
}
