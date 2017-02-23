<?php


namespace App\Transactions;


class Validator
{
    /**
     * @param array $data
     * @return bool
     */
    public static function validate(array $data)
    {
        return count($data) == 6; // @todo validate other params
    }
}