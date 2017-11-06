<?php

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use UMA\DCA\Bitcoin;
use UMA\DCA\Bitstamp\Request\GetTicker;
use UMA\DCA\Dollar;
use UMA\DCA\ConverterInterface;

/**
 * Bitstamp ConverterInterface implementation.
 */
class Converter implements ConverterInterface
{
    /**
     * @var Client
     */
    private $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * {@inheritdoc}
     */
    public function USDBTC(Dollar $dollar): Bitcoin
    {
        $exchangeRate = (float) json_decode((string) $this->http->send(new GetTicker)->getBody())->last;

        return $dollar->toBitcoin($exchangeRate);
    }
}
