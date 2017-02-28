<?php


namespace App\Commissions\Operations;


use App\Transactions\TransactionsRepository;
use App\Transactions\Transaction;
use Exception;

interface OperationInterface
{
    /**
     * @param Transaction $transaction
     * @param TransactionsRepository $repository
     */
    public function __construct(Transaction $transaction, TransactionsRepository & $repository);

    /**
     * @return float|int
     * @throws Exception
     */
    public function calc();
}
