<?php

declare(strict_types=1);

namespace UMA\Tests\DCA\Bitstamp;

use PHPUnit\Framework\TestCase;
use UMA\DCA\Bitstamp\Auth;
use UMA\DCA\Bitstamp\NonceGeneratorInterface;

final class AuthTest extends TestCase
{
    public function testAuthComputesCorrectCredentials()
    {
        $deterministicNonceGenerator = new class implements NonceGeneratorInterface {
            public function next(): int
            {
                return 1500000000000000;
            }
        };

        $auth = new Auth('123', 'abc', 'xyz', $deterministicNonceGenerator);

        $expected = [
            'key' => '123',
            'signature' => 'A3011914998470DA8C1B14F47A237A1C8428F93CBC6B98A76C84561FC929F024',
            'nonce' => '1500000000000000'
        ];

        self::assertSame($expected, $auth->getCredentials());
    }
}
