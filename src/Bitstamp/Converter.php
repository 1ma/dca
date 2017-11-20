<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use UMA\DCA\Bitstamp\Request\GetTicker;
use UMA\DCA\Contract\ConverterInterface;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\Dollar;

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
