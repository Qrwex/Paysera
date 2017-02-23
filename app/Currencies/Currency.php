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
     * @param int $precision
     * @return float
     */
    public static function convert($from, $to, $amount, $precision = 2)
    {
        if (self::DEFAULT_CURRENCY != $from && self::DEFAULT_CURRENCY != $to) {
            // I have rates of the euro only.
            $amount = self::convert($from, self::DEFAULT_CURRENCY, $amount, $precision);
            $from = self::DEFAULT_CURRENCY;
        }

        $converted = ($amount * Exchange\Rate::get($from)) / Exchange\Rate::get($to);

        /**
         * @todo Rounding to up!!!! VERY VERY IMPORTANT!!!
         * ???
         * TASK.md:53
         */

        $rounded = round($converted, $precision);

        return $rounded;
    }
}