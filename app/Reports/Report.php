<?php

namespace App\Reports;


use App\Commissions\In;
use App\Commissions\Out;
use App\Currencies\Currency;
use App\Transactions\DataList;
use App\Transactions\Transaction;
use App\Transactions\TransactionFactory;
use App\Users\User;
use League\Csv\Reader;
use Exception;

class Report
{
    /**
     * @var Reader
     */
    protected $rows;

    /**
     * Transactions list needed for calculations.
     * @var DataList
     */
    protected $list;

    /**
     * Report constructor.
     * @param Reader $rows
     */
    public function __construct(Reader $rows)
    {
        $this->rows = $rows;
        $this->list = new DataList;
    }

    /**
     * Dumps report data.
     * @throws Exception
     */
    public function dump()
    {
        foreach ($this->rows as $row) {

            $transaction = TransactionFactory::get($row);

            if (!$transaction) {
                throw new Exception('Bad data');
            }

            switch ($transaction->getType()) {
                case Transaction::TYPE_IN:

                    $tax = In::calc($transaction->getAmount(), $transaction->getCurrency());

                    break;
                case Transaction::TYPE_OUT:
                    $tax = Out::calc(
                        $this->getTransactionAmt($transaction), $transaction->getCurrency(), $transaction->getUserType()
                    );
                    break;
                default:
                    throw new Exception('Unknown operation type');
            }

            dump(
                rtn($tax, 0.01)
            );

            $this->list->append($transaction);
        }
    }

    /**
     * @param Transaction $transaction
     * @return float
     * @throws Exception
     */
    private function getTransactionAmt(Transaction $transaction)
    {
        $user_week_list = $this->list->getUserWeekList($transaction);

        switch ($transaction->getUserType()) {
            case User::TYPE_LEGAL:
                return $transaction->getAmount();
            case User::TYPE_NATURAL:

                $amount_default = Currency::convert(
                    $transaction->getCurrency(), Currency::DEFAULT_CURRENCY, $transaction->getAmount()
                );

                if ($user_week_list)
                {
                    if (count($user_week_list) >= Out::FREE_NATURAL_WEEK_COUNT)
                    {
                        return $transaction->getAmount();
                    }
                    elseif ($user_week_list['total_amt'] > Out::FREE_NATURAL_WEEK_LIMIT_AMT)
                    {
                        return $transaction->getAmount();
                    }
                    elseif ($user_week_list['total_amt'] <= Out::FREE_NATURAL_WEEK_LIMIT_AMT)
                    {
                        $total = $user_week_list['total_amt'] + $amount_default;

                        if ($total > Out::FREE_NATURAL_WEEK_LIMIT_AMT)
                        {
                            $diff = $total - Out::FREE_NATURAL_WEEK_LIMIT_AMT;

                            return Currency::convert(
                                Currency::DEFAULT_CURRENCY, $transaction->getCurrency(), $diff
                            );
                        }
                    }
                }
                else
                {
                    if ($amount_default > Out::FREE_NATURAL_WEEK_LIMIT_AMT)
                    {
                        $diff = $amount_default - Out::FREE_NATURAL_WEEK_LIMIT_AMT;

                        return Currency::convert(
                            Currency::DEFAULT_CURRENCY, $transaction->getCurrency(), $diff
                        );
                    }
                }

                return 0;

            default:
                throw new Exception('Unknown user type');
        }
    }
}