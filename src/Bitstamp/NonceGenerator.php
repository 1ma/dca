<?php

declare (strict_types=1);

namespace UMA\DCA\Bitstamp;

/**
 * The NonceGenerator returns integers that are
 * suitable to be used as nonces in authenticated
 * requests against the Bitstamp HTTP API.
 */
class NonceGenerator
{
    private $last = 0;

    /**
     * @return int
     */
    public function next(): int
    {
        $time = $this->currentTimeMicros();

        $next = $time > $this->last ?
            $time : $this->last + 1;

        $this->last = $next;

        return $next;
    }

    private function currentTimeMicros(): int
    {
        return (int)(1000000 * microtime(true));
    }
}
