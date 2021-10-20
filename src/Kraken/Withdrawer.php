<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use UMA\DCA\Kraken\Request\WithdrawalOrder;
use UMA\DCA\Model\Address;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\WithdrawerInterface;

/**
 * Kraken implementation of the WithdrawerInterface.
 */
final class Withdrawer implements WithdrawerInterface
{
    private Auth $auth;
    private Client $http;

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
        return $this->http->send(new WithdrawalOrder($this->auth, $bitcoin, $address));
    }
}
