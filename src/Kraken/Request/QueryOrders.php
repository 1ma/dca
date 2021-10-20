<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Kraken\Auth;
use function implode;

/**
 * An HTTP request for retrieving the current status of order transaction.
 *
 * @see https://www.kraken.com/help/api#query-orders-info
 */
final class QueryOrders extends Request
{
    private const QUERY_ORDERS = 'QueryOrders';
    private const ENDPOINT = 'https://api.kraken.com/0/private/'. self::QUERY_ORDERS;

    public function __construct(Auth $auth, array $txIdList)
    {
        $queryPrivate = $auth->getQuery(self::QUERY_ORDERS, [
            'txid' => implode(',', $txIdList)
        ]);

        parent::__construct(
            'POST',
            self::ENDPOINT,
            $queryPrivate->getHeaders(),
            $queryPrivate->getBody()
        );
    }
}
