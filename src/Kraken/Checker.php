<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use UMA\DCA\Kraken\Request\QueryOrders;

class Checker
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Client
     */
    private $http;

    public function __construct(Auth $auth, Client $http)
    {
        $this->auth = $auth;
        $this->http = $http;
    }

    /**
     * {@inheritdoc}
     */
    public function check(string $txIdList): ResponseInterface
    {
        return $this->http->send(
            new QueryOrders($this->auth, [$txIdList])
        );
    }
}