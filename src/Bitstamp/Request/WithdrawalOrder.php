<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Address;
use UMA\DCA\Bitcoin;
use UMA\DCA\Bitstamp\Auth;

/**
 * An HTTP request for withdrawing some bitcoin to the given address.
 *
 * @see https://www.bitstamp.net/api/#bitcoin-withdrawal
 */
class WithdrawalOrder extends Request
{
    const ENDPOINT = 'https://www.bitstamp.net/api/bitcoin_withdrawal/';

    public function __construct(Auth $auth, Bitcoin $amount, Address $address)
    {
        $body = http_build_query(
            array_merge($auth->getCredentials(), [
                'amount' => (string) $amount,
                'address' => (string) $address,
                'instant' => '0'
            ])
        );

        parent::__construct(
            'POST',
            self::ENDPOINT,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            $body
        );
    }
}
