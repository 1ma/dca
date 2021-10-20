<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Kraken\Auth;
use UMA\DCA\Model\Bitcoin;

/**
 * An HTTP request for buying some amount of bitcoin at market price.
 *
 * @see https://www.kraken.com/help/api#add-standard-order
 */
final class BuyOrder extends Request
{
    private const BUY_ORDER = 'AddOrder';
    private const ENDPOINT = 'https://api.kraken.com/0/private/'. self::BUY_ORDER;

    public function __construct(Auth $auth, Bitcoin $amount)
    {
        $queryPrivate = $auth->getQuery(self::BUY_ORDER, [
            'pair' => 'XXBTZUSD',
            'type' => 'buy',
            'ordertype' => 'market',
            'oflags' => 'viqc',
            'volume' => (string) $amount,
            'starttm' => 0
        ]);

        parent::__construct(
            'POST',
            self::ENDPOINT,
            $queryPrivate->getHeaders(),
            $queryPrivate->getBody()
        );
    }
}
