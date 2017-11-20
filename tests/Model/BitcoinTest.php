<?php

declare(strict_types=1);

namespace UMA\Tests\DCA\Model;

use PHPUnit\Framework\TestCase;
use UMA\DCA\Model\Bitcoin;

class BitcoinTest extends TestCase
{
    /**
     * @dataProvider satoshiProvider
     */
    public function testBitcoinIsFormattedProperly(string $expected, int $satoshi)
    {
        self::assertSame($expected, (string) Bitcoin::fromSatoshi($satoshi));
    }

    public function satoshiProvider(): array
    {
        return [
            ['0.00000001', 1],
            ['21000000.00000000', Bitcoin::MAXIMUM_AMOUNT],
        ];
    }
}
