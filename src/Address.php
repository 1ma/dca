<?php

declare(strict_types=1);

namespace UMA\DCA;

use LinusU\Bitcoin\AddressValidator;

/**
 * Value object representing a valid bitcoin address.
 */
class Address
{
    /**
     * @var string
     */
    private $address;

    private function __construct(string $candidate)
    {
        if (false === AddressValidator::isValid($candidate)) {
            throw new \InvalidArgumentException("Invalid bitcoin address received. Got: $candidate");
        }

        $this->address = $candidate;
    }

    public static function fromString(string $candidate): Address
    {
        return new self($candidate);
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
