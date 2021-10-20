<?php

declare(strict_types=1);

namespace UMA\DCA\Model;

use InvalidArgumentException;
use function number_format;

/**
 * Value object representing a certain amount of Euro.
 */
final class Euro
{
    /**
     * Each euro can be subdivided into 100 cents.
     */
    private const BASIC_UNIT = 100;

    private int $cents;

    private function __construct(int $cents)
    {
        if ($cents <= 0) {
            throw new InvalidArgumentException("Invalid cent amount received. Got: $cents");
        }

        $this->cents = $cents;
    }

    public static function fromCents(int $cents): Euro
    {
        return new self($cents);
    }

    public static function fromFloat(float $euros): Euro
    {
        return new self((int)(self::BASIC_UNIT * $euros));
    }

    public function __toString(): string
    {
        return number_format($this->cents / self::BASIC_UNIT, 2, '.', '');
    }

    public function toBitcoin(float $btcusd): Bitcoin
    {
        return Bitcoin::fromFloat($this->asFloat() / $btcusd);
    }

    public function asCents(): int
    {
        return $this->cents;
    }

    public function asFloat(): float
    {
        return $this->cents / self::BASIC_UNIT;
    }
}
