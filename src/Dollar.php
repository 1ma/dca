<?php

namespace UMA\DCA;

/**
 * Value object representing a certain amount of US dollars.
 */
class Dollar
{
    /**
     * Each dollar can be subdivided into 100 cents.
     */
    const BASIC_UNIT = 100;

    /**
     * @var int
     */
    private $cents;

    private function __construct(int $cents)
    {
        if ($cents <= 0) {
            throw new \InvalidArgumentException('');
        }

        $this->cents = $cents;
    }

    public static function fromCents(int $cents): Dollar
    {
        return new self($cents);
    }

    public static function fromFloat(float $dollars): Dollar
    {
        return new self((int)(self::BASIC_UNIT * $dollars));
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
