<?php

declare(strict_types=1);

namespace UMA\DCA\Model;

use Psr\Http\Message\ResponseInterface;

interface BuyerInterface
{
    /**
     * Buys BTC at market price with the given $dollar amount.
     *
     * Returns the HTTP response from the exchange.
     */
    public function buy(Dollar $dollar): ResponseInterface;
}
