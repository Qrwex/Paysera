<?php

namespace App\Reports;


use App\Commissions\CalcFactory;
use App\Transactions\TransactionFactory;
use League\Csv\Reader;
use Exception;

class Report
{
    /**
     * @var Reader
     */
    protected $rows;

    /**
     * Report constructor.
     * @param Reader $rows
     */
    public function __construct(Reader $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Dumps report data.
     * @throws Exception
     */
    public function dump()
    {
        $calc = CalcFactory::get();

        foreach ($this->rows as $row) {

            if (!$transaction = TransactionFactory::get($row)) {
                throw new Exception('Bad data');
            }

            $tax = $calc->service($transaction)->calc();

            dump(
                rtn($tax, 0.01)
            );
        }
    }
}
