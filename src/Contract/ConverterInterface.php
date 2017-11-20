<?php

declare(strict_types=1);

namespace UMA\DCA\Contract;

use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\Dollar;

interface ConverterInterface
{
    /**
     * Converts the given amount of Dollars to Bitcoin
     * at the current exchange rate.
     */
    public function USDBTC(Dollar $dollar): Bitcoin;
}
