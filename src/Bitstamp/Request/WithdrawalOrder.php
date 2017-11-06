<?php

declare (strict_types=1);

namespace UMA\DCA\Bitstamp\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Bitstamp\Auth;

/**
 * An HTTP request for withdrawing some bitcoin from a given account.
 *
 * @see https://www.bitstamp.net/api/#bitcoin-withdrawal
 */
class WithdrawalOrder extends Request
{
    const ENDPOINT = 'https://www.bitstamp.net/api/bitcoin_withdrawal/';

    public function __construct(Auth $auth)
    {
        parent::__construct(
            'POST', self::ENDPOINT,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query($auth->getCredentials())
        );
    }
}
