<?php

namespace App\Reports;


use League\Csv\Reader;

class ReportFactory
{
    /**
     * @param Reader $transactions
     * @return Report
     */
    public static function get(Reader $transactions)
    {
        return new Report($transactions);
    }
}