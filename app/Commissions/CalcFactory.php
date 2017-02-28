<?php

namespace App\Commissions;


class CalcFactory
{
    /**
     * @return Calc
     */
    public static function get()
    {
        return new Calc();
    }
}
