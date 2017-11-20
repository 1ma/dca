<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Bitstamp\Auth;
use UMA\DCA\Model\Bitcoin;

/**
 * An HTTP request for buying some amount of bitcoin at market price.
 *
 * @see https://www.bitstamp.net/api/#buy-market-order
 */
class BuyOrder extends Request
{
    const ENDPOINT = 'https://www.bitstamp.net/api/v2/buy/market/btcusd/';

    public function __construct(Auth $auth, Bitcoin $amount)
    {
        parent::__construct(
            'POST',
            self::ENDPOINT,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query(
                array_merge($auth->getCredentials(), ['amount' => (string)$amount])
            )
        );
    }
}
