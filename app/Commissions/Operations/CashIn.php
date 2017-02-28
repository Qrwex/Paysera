<?php


namespace App\Commissions\Operations;


use App\Currencies\Currency;
use App\Transactions\DataList;
use App\Transactions\Transaction;

class CashIn implements OperationInterface
{
    const RATE = 0.03 / 100;
    const MAX_TAX_AMT = 5;
    const MAX_TAX_CUR = Currency::DEFAULT_CURRENCY;

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * Transactions list needed for calculations.
     * @var DataList
     */
    protected $list;

    /**
     * CashIn Constructor
     * @param Transaction $transaction
     * @param DataList $list
     */
    public function __construct(Transaction $transaction, DataList & $list)
    {
        $this->transaction = $transaction;
        $this->list = &$list;
    }

    /**
     * Calculate In operation commission.
     * @return float|int
     */
    public function calc()
    {
        // Get charged amount with applied free limits rules.
        $tax_amt = $this->getChargedAmount() * self::RATE;

        // Append transaction data to list. Needed for a free limits calculations.
        $this->getList()->append(
            $this->getTransaction()
        );

        // Checking for a min/max tax limits.
        $max_tax_amt = self::MAX_TAX_AMT;

        if ($this->getTransaction()->getCurrency() != self::MAX_TAX_CUR) {
            $max_tax_amt = Currency::convert(
                self::MAX_TAX_CUR, $this->getTransaction()->getCurrency(), self::MAX_TAX_AMT
            );
        }

        if ($tax_amt > $max_tax_amt) {
            return $max_tax_amt;
        }

        return $tax_amt;
    }

    /**
     * Get charged amount with applied free limit rules. No rules at the moment.
     * @return float
     */
    private function getChargedAmount()
    {
        return $this->getTransaction()->getAmount();
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @return DataList
     */
    private function getList()
    {
        return $this->list;
    }
}
