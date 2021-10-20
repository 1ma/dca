<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Request;

use GuzzleHttp\Psr7\Request;
use UMA\DCA\Kraken\Auth;
use UMA\DCA\Model\Address;
use UMA\DCA\Model\Bitcoin;

/**
 * An HTTP request for withdrawing some bitcoin to the given Withdrawkey configured previously on kraken control panel.
 *
 * @see https://www.kraken.com/help/api#withdraw-funds
 */
final class WithdrawalOrder extends Request
{
    private const WITHDRAWAL = 'Withdraw';
    private const ENDPOINT = 'https://api.kraken.com/0/private/'. self::WITHDRAWAL;

    public function __construct(Auth $auth, Bitcoin $amount, Address $address)
    {
        $queryPrivate = $auth->getQuery(self::WITHDRAWAL, [
            'asset' => 'XBT',
            'key' => (string) $address,
            'amount' => (string) $amount
        ]);

        parent::__construct(
            'POST',
            self::ENDPOINT,
            $queryPrivate->getHeaders(),
            $queryPrivate->getBody()
        );
    }
}
