<?php

declare(strict_types=1);

namespace UMA\DCA;

use Psr\Http\Message\ResponseInterface;

interface WithdrawerInterface
{
    /**
     * Moves $bitcoin from an exchange to the given $address.
     *
     * Returns the HTTP response from the exchange.
     */
    public function withdraw(Bitcoin $bitcoin, Address $address): ResponseInterface;
}
