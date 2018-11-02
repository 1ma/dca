<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use GuzzleHttp\Client;
use UMA\DCA\Kraken\Request\GetTicker;
use UMA\DCA\Model\ConverterInterface;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\Dollar;

/**
 * Kraken ConverterInterface implementation.
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
        $exchangeRate = (float) json_decode((string) $this->http->send(new GetTicker)->getBody(), true)["c"][0];

        return $dollar->toBitcoin($exchangeRate);
    }
}
