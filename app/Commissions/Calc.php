<?php

namespace App\Commissions;


use App\Commissions\Operations\CashIn;
use App\Commissions\Operations\CashOut;
use App\Transactions\DataList;
use App\Transactions\Transaction;
use Exception;

class Calc
{
    /**
     * Operations services
     * @var array
     */
    protected $services = [
        Transaction::TYPE_IN => CashIn::class,
        Transaction::TYPE_OUT => CashOut::class,
    ];

    /**
     * @var DataList
     */
    protected $list;

    /**
     * Calc Constructor
     */
    public function __construct()
    {
        $this->list = new DataList;
    }

    /**
     * @param Transaction $transaction
     * @return Operations\OperationInterface
     * @throws Exception
     */
    public function service(Transaction $transaction)
    {
        if (!isset($this->services[$transaction->getType()])) {
            throw new Exception('Unknown operation type');
        }

        $facade = $this->services[$transaction->getType()];

        if (!class_exists($facade)) {
            throw new Exception('Class not found');
        }
        /**
         * @var $class \App\Commissions\Operations\OperationInterface
         */
        $class = new $facade($transaction, $this->list);

        return $class;
    }
}
