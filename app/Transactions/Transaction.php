<?php


namespace App\Transactions;


use DateTime;

class Transaction
{
    /**
     * string
     */
    const TYPE_IN = 'cash_in';

    const TYPE_OUT = 'cash_out';

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var integer
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $user_type;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * Transaction constructor.
     * @param $date string
     * @param $user_id integer
     * @param $user_type string
     * @param $type string
     * @param $amount float
     * @param $currency string
     */
    public function __construct($date, $user_id, $user_type, $type, $amount, $currency)
    {
        $this->date = new DateTime($date);
        $this->user_id = (int)$user_id;
        $this->user_type = $user_type;
        $this->type = $type;
        $this->amount = (float)$amount;
        $this->currency = $currency;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getUserType()
    {
        return $this->user_type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}