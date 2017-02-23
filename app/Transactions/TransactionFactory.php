<?php


namespace App\Transactions;

use ReflectionClass;

class TransactionFactory
{
    /**
     * Create Transaction object from data array.
     * @param array $data
     * @return Transaction|null
     */
    public static function get(array $data)
    {
        if (!Validator::validate($data)) {
            return null;
        }

        $reflect = new ReflectionClass(Transaction::class);
        $transaction = $reflect->newInstanceArgs($data);
        /**
         * @var $transaction Transaction
         */
        return $transaction;
    }
}