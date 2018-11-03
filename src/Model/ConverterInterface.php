<?php

declare(strict_types=1);

namespace UMA\DCA\Model;

interface ConverterInterface
{
    /**
     * Converts the given amount of Dollars to Bitcoin
     * at the current exchange rate.
     */
    public function USDBTC(Dollar $dollar): Bitcoin;

    /**
     * Converts the given amount of Euros to Bitcoin
     * at the current exchange rate.
     */
    public function EURBTC(Euro $euro): Bitcoin;
}
