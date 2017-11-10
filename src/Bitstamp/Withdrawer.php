<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use UMA\DCA\Address;
use UMA\DCA\Bitcoin;
use UMA\DCA\Bitstamp\Request\WithdrawalOrder;
use UMA\DCA\WithdrawerInterface;

/**
 * Bitstamp implementation of the WithdrawerInterface.
 */
class Withdrawer implements WithdrawerInterface
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
    public function withdraw(Bitcoin $bitcoin, Address $address): ResponseInterface
    {
        return $this->http->send(
            new WithdrawalOrder($this->auth, $bitcoin, $address)
        );
    }
}
