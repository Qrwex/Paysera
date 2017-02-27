<?php


namespace App\Commissions;


use App\Currencies\Currency;

class In
{
    const RATE = 0.03 / 100;
    const MAX_TAX_AMT = 5;
    const MAX_TAX_CUR = Currency::DEFAULT_CURRENCY;

    /**
     * Calculate In operation commission.
     * @param $amount
     * @param $currency
     * @return int
     */
    public static function calc($amount, $currency)
    {
        $tax_amt = $amount * self::RATE;

        $max_tax_amt = self::MAX_TAX_AMT;

        if ($currency != self::MAX_TAX_CUR) {
            $max_tax_amt = Currency::convert(self::MAX_TAX_CUR, $currency, self::MAX_TAX_AMT);
        }

        if ($tax_amt > $max_tax_amt) {
            return $max_tax_amt;
        }

        return $tax_amt;
    }
}