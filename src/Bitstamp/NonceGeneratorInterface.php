<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

interface NonceGeneratorInterface
{
    public function next(): int;
}
