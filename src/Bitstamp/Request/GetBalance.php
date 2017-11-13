<?php

namespace UMA\DCA\Bitstamp\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Bitstamp\Auth;

class GetBalance extends Request
{
    const ENDPOINT = 'https://www.bitstamp.net/api/v2/balance/';

    public function __construct(Auth $auth)
    {
        parent::__construct(
            'POST',
            self::ENDPOINT,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query($auth->getCredentials())
        );
    }
}
