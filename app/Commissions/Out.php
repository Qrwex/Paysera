<?php


namespace App\Commissions;


use App\Currencies\Currency;
use App\Users\User;

class Out
{
    const RATE = 0.3 / 100;

    const FREE_NATURAL_WEEK_LIMIT_AMT = 1000.00;
    const FREE_NATURAL_WEEK_LIMIT_CUR = Currency::DEFAULT_CURRENCY;
    const FREE_NATURAL_WEEK_COUNT = 3;

    const MIN_LEGAL_TAX_AMT = 0.50;
    const MIN_LEGAL_TAX_CUR = Currency::DEFAULT_CURRENCY;

    /**
     * Calculate Out operation commission.
     * @param $amount
     * @param $currency
     * @param $user_type
     * @return float
     */
    public static function calc($amount, $currency, $user_type)
    {
        $tax_amt = $amount * self::RATE;

        if ($user_type == User::TYPE_LEGAL) {
            $min_tax_amt = self::MIN_LEGAL_TAX_AMT;

            if ($currency != self::MIN_LEGAL_TAX_CUR) {
                $min_tax_amt = Currency::convert(self::MIN_LEGAL_TAX_CUR, $currency, self::MIN_LEGAL_TAX_AMT);
            }

            if ($tax_amt < $min_tax_amt) {
                return $min_tax_amt;
            }
        }

        return $tax_amt;
    }
}