<?php


namespace App\Currencies;


class Currency
{

    /**
     * Default currency string
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * @param $from
     * @param $to
     * @param $amount
     * @return float
     */
    public static function convert($from, $to, $amount)
    {
        if (self::DEFAULT_CURRENCY != $from && self::DEFAULT_CURRENCY != $to) {
            // Having rates of the euro only so doing convert to euro.
            $amount = self::convert($from, self::DEFAULT_CURRENCY, $amount);
            $from = self::DEFAULT_CURRENCY;
        }

        $converted = ($amount * Exchange\Rate::get($to)) / Exchange\Rate::get($from);

        return $converted;
    }
}
