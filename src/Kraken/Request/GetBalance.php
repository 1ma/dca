<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Kraken\Auth;

/**
 * An HTTP Request to get the account balance
 *
 * @see https://www.kraken.com/help/api#get-account-balance
 *
 */
class GetBalance extends Request
{
    const BALANCE = 'Balance';

    const ENDPOINT = 'https://api.kraken.com/0/private/'. self::BALANCE;

    public function __construct(Auth $auth)
    {
        parent::__construct(
            'POST',
            self::ENDPOINT,
            $auth->getQuery(self::BALANCE, [])->getHeaders()
        );
    }
}
