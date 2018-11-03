<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Request;

use GuzzleHttp\Psr7\Request;

/**
 * An HTTP request for retrieving the current market price of bitcoin.
 *
 * @see https://www.bitstamp.net/api/#ticker
 */
class GetTicker extends Request
{
    const ENDPOINT = 'https://www.bitstamp.net/api/v2/ticker/btc%s/';

    public function __construct(string $currency)
    {
        if (!\in_array($currency, ['eur', 'usd'])) {
            throw new \LogicException("Invalid currency supplied. Got: $currency");
        }

        parent::__construct('GET', \sprintf(self::ENDPOINT, $currency));
    }
}
