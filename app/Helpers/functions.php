<?php

use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('rtn')) {
    /**
     * Round to nearest
     * @param $amount
     * @param float $nearest
     * @return float
     */
    function rtn($amount, $nearest)
    {
        return ceil($amount / $nearest) * $nearest;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($x) {
            VarDumper::dump($x);
        }, func_get_args());

        die(1);
    }
}
