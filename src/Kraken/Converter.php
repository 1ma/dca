<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use GuzzleHttp\Client;
use LogicException;
use UMA\DCA\Kraken\Request\GetTicker;
use UMA\DCA\Model\ConverterInterface;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\Dollar;
use UMA\DCA\Model\Euro;
use function json_decode;

/**
 * Kraken ConverterInterface implementation.
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
        $exchangeRate = (float) json_decode((string) $this->http->send(new GetTicker)->getBody(), true)["c"][0];

        return $dollar->toBitcoin($exchangeRate);
    }

    /**
     * {@inheritdoc}
     */
    public function EURBTC(Euro $euro): Bitcoin
    {
        throw new LogicException('Not implemented yet');
    }
}
