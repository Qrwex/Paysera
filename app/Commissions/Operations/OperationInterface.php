<?php


namespace App\Commissions\Operations;


use App\Transactions\DataList;
use App\Transactions\Transaction;
use Exception;

interface OperationInterface
{
    /**
     * @param Transaction $transaction
     * @param DataList $data_list
     */
    public function __construct(Transaction $transaction, DataList & $data_list);

    /**
     * @return float|int
     * @throws Exception
     */
    public function calc();
}
