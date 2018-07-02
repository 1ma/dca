<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

/**
 * The NonceGenerator returns integers that are
 * suitable to be used as nonces in authenticated
 * requests against the Kraken HTTP API.
 */
class NonceGenerator
{
    private $last = 0;

    /**
     * @return string
     */
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
