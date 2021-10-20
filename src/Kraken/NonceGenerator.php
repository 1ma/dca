<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use function explode;
use function microtime;
use function str_pad;
use function substr;

/**
 * The NonceGenerator returns integers that are
 * suitable to be used as nonces in authenticated
 * requests against the Kraken HTTP API.
 */
final class NonceGenerator
{
    private int $last = 0;

    public function next(): string
    {
        $time = microtime();

        $next = $time > $this->last ?
            $time : $this->last + 1;

        $this->last = $next;

        $nonce = explode(' ', microtime());

        return $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');
    }

}
