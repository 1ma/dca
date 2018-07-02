<?php

declare(strict_types=1);

namespace UMA\Tests\DCA\Bitstamp;

use PHPUnit\Framework\TestCase;
use UMA\DCA\Bitstamp\NonceGenerator;

class NonceGeneratorTest extends TestCase
{
    public function testNoncesAreUniqueAndMonotonic()
    {
        $seen = [];
        $maxSeen = 0;
        $provider = new NonceGenerator();

        for ($i = 0; $i < 10000; ++$i) {
            $nonce = $provider->next();

            self::assertArrayNotHasKey($nonce, $seen);

            $seen[$nonce] = true;

            self::assertTrue($nonce > $maxSeen);

            $maxSeen = $nonce;
        }
    }
}
