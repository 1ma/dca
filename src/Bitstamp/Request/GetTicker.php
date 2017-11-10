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
    const ENDPOINT = 'https://www.bitstamp.net/api/v2/ticker/btcusd/';

    public function __construct()
    {
        parent::__construct('GET', self::ENDPOINT);
    }
}
