<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use UMA\DCA\Kraken\Request\BuyOrder;
use UMA\DCA\Model\BuyerInterface;
use UMA\DCA\Model\ConverterInterface;
use UMA\DCA\Model\Dollar;

/**
 * Kraken implementation of the BuyerInterface.
 */
final class Buyer implements BuyerInterface
{
    private Auth $auth;
    private Client $http;
    private ConverterInterface $converter;

    public function __construct(Auth $auth, Client $http, ConverterInterface $converter)
    {
        $this->auth = $auth;
        $this->http = $http;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function buy(Dollar $dollar): ResponseInterface
    {
        return $this->http->send(new BuyOrder($this->auth, $this->converter->USDBTC($dollar))
        );
    }
}
