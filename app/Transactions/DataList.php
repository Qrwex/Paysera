<?php


namespace App\Transactions;


use App\Currencies\Currency;

class DataList
{
    const DEBUG = false;

    /**
     * @var array
     */
    protected $list = [];

    /**
     * Append active transaction to a data list.
     * @param Transaction $transaction
     */
    public function append(Transaction $transaction)
    {
        $dt = $transaction->getDate();

        $w = $dt->format('W'); // week
        $y = $dt->format('Y'); // year

        $u = $transaction->getUserId(); // user
        $t = $transaction->getUserType(); // user type
        $o = $transaction->getType(); // operation type

        $this->list[$o][$t][$y][$w][$u]['list'][] = $transaction;

        if (!isset($this->list[$o][$t][$y][$w][$u]['total_amt'])) {
            $this->list[$o][$t][$y][$w][$u]['total_amt'] = 0;
        }

        $amount = Currency::convert(
            $transaction->getCurrency(), Currency::DEFAULT_CURRENCY, $transaction->getAmount()
        );

        $this->list[$o][$t][$y][$w][$u]['total_amt'] += $amount;
    }

    /**
     * Get active transaction user week list.
     * @param Transaction $transaction
     * @return array
     */
    public function getUserWeekList(Transaction $transaction)
    {
        $dt = $transaction->getDate();

        $w = $dt->format('W'); // week
        $y = $dt->format('Y'); // year

        $u = $transaction->getUserId(); // user
        $t = $transaction->getUserType(); // user type
        $o = $transaction->getType(); // operation type

        return isset($this->list[$o][$t][$y][$w][$u]) ?
            $this->list[$o][$t][$y][$w][$u] : [];
    }

    /**
     * Debug.
     */
    public function __destruct()
    {
        if (self::DEBUG) {
            dd($this->list);
        }
    }
}
