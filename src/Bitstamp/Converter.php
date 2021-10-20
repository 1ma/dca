<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use UMA\DCA\Bitstamp\Request\GetTicker;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\ConverterInterface;
use UMA\DCA\Model\Dollar;
use UMA\DCA\Model\Euro;
use function json_decode;

/**
 * Bitstamp ConverterInterface implementation.
 */
final class Converter implements ConverterInterface
{
    private Client $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * {@inheritdoc}
     */
    public function USDBTC(Dollar $dollar): Bitcoin
    {
        $exchangeRate = (float) json_decode((string) $this->http->send(new GetTicker('usd'))->getBody())->last;

        return $dollar->toBitcoin($exchangeRate);
    }

    /**
     * {@inheritdoc}
     */
    public function EURBTC(Euro $euro): Bitcoin
    {
        $exchangeRate = (float) json_decode((string) $this->http->send(new GetTicker('eur'))->getBody())->last;

        return $euro->toBitcoin($exchangeRate);
    }
}
