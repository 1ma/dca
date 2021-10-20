<?php

declare(strict_types=1);

namespace UMA\DCA\Model;

use InvalidArgumentException;
use function number_format;

/**
 * Value object representing a certain amount of bitcoin.
 */
final class Bitcoin
{
    /**
     * Each Bitcoin can be subdivided in 100 million units, called 'satoshi'.
     */
    private const BASIC_UNIT = 10 ** 8;

    /**
     * There can be only 21 million bitcoin in existence, or 21 * (10 ** 14) satoshi.
     */
    public const MAXIMUM_AMOUNT = 21_000_000 * self::BASIC_UNIT;

    private int $satoshis;

    private function __construct(int $satoshis)
    {
        if ($satoshis <= 0 || $satoshis > self::MAXIMUM_AMOUNT) {
            throw new InvalidArgumentException("Invalid satoshi amount received. Got: $satoshis");
        }

        $this->satoshis = $satoshis;
    }

    public static function fromSatoshi(int $satoshis): Bitcoin
    {
        return new self($satoshis);
    }

    public static function fromFloat(float $bitcoin): Bitcoin
    {
        return new self((int)(self::BASIC_UNIT * $bitcoin));
    }

    public function __toString(): string
    {
        return number_format($this->satoshis / self::BASIC_UNIT, 8, '.', '');
    }

    public function toDollar(float $btcusd): Dollar
    {
        return Dollar::fromFloat($this->asFloat() * $btcusd);
    }

    public function asSatoshi(): int
    {
        return $this->satoshis;
    }

    public function asFloat(): float
    {
        return $this->satoshis / self::BASIC_UNIT;
    }
}
