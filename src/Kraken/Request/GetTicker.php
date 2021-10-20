<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Request;

use GuzzleHttp\Psr7\Request;

/**
 * An HTTP request for retrieving the current market price of bitcoin.
 *
 * @see https://www.kraken.com/help/api#get-ticker-info
 */
final class GetTicker extends Request
{
    private const ENDPOINT = 'https://api.kraken.com/0/public/Ticker?pair=XXBTZUSD';

    public function __construct()
    {
        parent::__construct('GET', self::ENDPOINT);
    }
}
