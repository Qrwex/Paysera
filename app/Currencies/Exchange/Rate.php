<?php


namespace App\Currencies\Exchange;

use App\Currencies\Currency;
use Exception;


class Rate
{
    /**
     * Exchange rates of the euro against foreign currencies.
     * @param $currency
     * @return float|int
     * @throws Exception
     */
    public static function get($currency)
    {
        // There should be used external sources.

        $currency = strtoupper($currency);

        switch ($currency) {
            case 'USD':
                return 1.1497;
            case 'JPY':
                return 129.53;
            case Currency::DEFAULT_CURRENCY:
                return 1;
            default:
                throw new Exception('Invalid currency');
        }
    }
}