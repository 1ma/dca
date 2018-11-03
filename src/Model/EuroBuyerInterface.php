<?php

declare(strict_types=1);

namespace UMA\DCA\Model;

use Psr\Http\Message\ResponseInterface;

interface EuroBuyerInterface
{
    /**
     * Buys BTC at market price with the given $euro amount.
     *
     * Returns the HTTP response from the exchange.
     */
    public function buy(Euro $euro): ResponseInterface;
}
