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
class BuyOrder extends Request
{
    const BUYORDER = 'AddOrder';

    const ENDPOINT = 'https://api.kraken.com/0/private/'. self::BUYORDER;

    public function __construct(Auth $auth, Bitcoin $amount)
    {
        $queryPrivate = $auth->getQuery(self::BUYORDER, [
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
