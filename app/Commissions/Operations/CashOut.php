<?php


namespace App\Commissions\Operations;


use App\Currencies\Currency;
use App\Transactions\TransactionsRepository;
use App\Transactions\Transaction;
use App\Users\User;
use Exception;

class CashOut implements OperationInterface
{
    const RATE = 0.3 / 100;

    const FREE_NATURAL_WEEK_LIMIT_AMT = 1000.00;
    const FREE_NATURAL_WEEK_LIMIT_CUR = Currency::DEFAULT_CURRENCY;
    const FREE_NATURAL_WEEK_COUNT = 3;

    const MIN_LEGAL_TAX_AMT = 0.50;
    const MIN_LEGAL_TAX_CUR = Currency::DEFAULT_CURRENCY;

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * Transactions repository needed for calculations.
     * @var TransactionsRepository
     */
    protected $repository;

    /**
     * CashOut Constructor
     * @param Transaction $transaction
     * @param TransactionsRepository $repository
     */
    public function __construct(Transaction $transaction, TransactionsRepository & $repository)
    {
        $this->transaction = $transaction;
        $this->repository = &$repository;
    }

    /**
     * Calculate cash out operation commission.
     * @return float
     */
    public function calc()
    {
        // Get charged amount with applied free limits rules.
        $tax_amt = $this->getChargedAmount() * self::RATE;

        // Append transaction data to repository. Needed for a free limits calculations.
        $this->getRepository()->append(
            $this->getTransaction()
        );

        // Checking for a min/max tax limits.
        if ($this->getTransaction()->getUserType() == User::TYPE_LEGAL) {
            $min_tax_amt = self::MIN_LEGAL_TAX_AMT;

            if ($this->getTransaction()->getCurrency() != self::MIN_LEGAL_TAX_CUR) {
                $min_tax_amt = Currency::convert(
                    self::MIN_LEGAL_TAX_CUR, $this->getTransaction()->getCurrency(), self::MIN_LEGAL_TAX_AMT
                );
            }

            if ($tax_amt < $min_tax_amt) {
                return $min_tax_amt;
            }
        }

        return $tax_amt;
    }

    /**
     * Get charged amount with applied free limit rules.
     * @return float|int
     * @throws Exception
     */
    private function getChargedAmount()
    {
        $user_week_list = $this->getRepository()->getUserWeekList($this->getTransaction());

        switch ($this->getTransaction()->getUserType()) {
            case User::TYPE_LEGAL:
                // No limits rules yet.
                return $this->getTransaction()->getAmount();
            case User::TYPE_NATURAL:

                $amount_default = $this->getDefaultCurrencyAmount();

                /**
                 * Check natural user week limits.
                 */
                if ($user_week_list) {

                    if (count($user_week_list) >= CashOut::FREE_NATURAL_WEEK_COUNT) {
                        /**
                         * Free cash out count limit exceeded
                         * Charge active transaction.
                         */

                        return $this->getTransaction()->getAmount();

                    } elseif ($user_week_list['total_amt'] > CashOut::FREE_NATURAL_WEEK_LIMIT_AMT) {
                        /**
                         * Free cash out week limit exceeded already
                         * Charge active transaction.
                         */

                        return $this->getTransaction()->getAmount();

                    } elseif ($user_week_list['total_amt'] <= CashOut::FREE_NATURAL_WEEK_LIMIT_AMT) {

                        $total = $user_week_list['total_amt'] + $amount_default;

                        if ($total > CashOut::FREE_NATURAL_WEEK_LIMIT_AMT) {
                            /**
                             * Free cash out week limit exceeded with an active transaction.
                             */

                            $diff = $total - CashOut::FREE_NATURAL_WEEK_LIMIT_AMT;

                            /**
                             * Diff amount charged
                             */
                            return Currency::convert(
                                Currency::DEFAULT_CURRENCY, $this->getTransaction()->getCurrency(), $diff
                            );
                        }
                    }
                } else {
                    if ($amount_default > CashOut::FREE_NATURAL_WEEK_LIMIT_AMT) {

                        /**
                         * Active transaction amount is much bigger than free cash out week limit.
                         */
                        $diff = $amount_default - CashOut::FREE_NATURAL_WEEK_LIMIT_AMT;

                        /**
                         * Charge difference.
                         */
                        return Currency::convert(
                            Currency::DEFAULT_CURRENCY, $this->getTransaction()->getCurrency(), $diff
                        );
                    }
                }

                return 0; // None of the rules were applied for charge.

            default:
                throw new Exception('Unknown user type');
        }
    }

    /**
     * @return Transaction
     */
    private function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @return float
     */
    private function getDefaultCurrencyAmount()
    {
        return Currency::convert(
            $this->getTransaction()->getCurrency(), Currency::DEFAULT_CURRENCY, $this->getTransaction()->getAmount()
        );
    }

    /**
     * @return TransactionsRepository
     */
    private function getRepository()
    {
        return $this->repository;
    }
}
