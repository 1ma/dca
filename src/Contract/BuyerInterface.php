<?php

declare(strict_types=1);

namespace UMA\DCA\Contract;

use Psr\Http\Message\ResponseInterface;
use UMA\DCA\Model\Dollar;

interface BuyerInterface
{
    /**
     * Buys BTC at market price with the given $dollar amount.
     *
     * Returns the HTTP response from the exchange.
     */
    public function buy(Dollar $dollar): ResponseInterface;
}
