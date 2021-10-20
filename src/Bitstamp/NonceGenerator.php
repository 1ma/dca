<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use function microtime;

/**
 * The NonceGenerator returns integers that are
 * suitable to be used as nonces in authenticated
 * requests against the Bitstamp HTTP API.
 */
class NonceGenerator implements NonceGeneratorInterface
{
    private const SECONDS_TO_MICROS = 1_000_000;

    private int $last = 0;

    public function next(): int
    {
        $time = (int)(self::SECONDS_TO_MICROS * microtime(true));

        $next = $time > $this->last ?
            $time : $this->last + 1;

        $this->last = $next;

        return $next;
    }
}
