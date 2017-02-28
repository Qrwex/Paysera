<?php

namespace Tests;

use App\Commissions\CalcFactory;
use App\Transactions\TransactionFactory;
use League\Csv\Reader;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\Commissions
 */
class CalcTest extends TestCase
{
    /** @test */
    public function testCalc()
    {
        $input_rows = Reader::createFromPath(__DIR__ . '/src/input.csv');
        $output_rows = Reader::createFromPath(__DIR__ . '/src/output.csv');

        $calc = CalcFactory::get();

        foreach ($input_rows as $key => $input_row) {
            $transaction = TransactionFactory::get($input_row);
            $result_tax = rtn($calc->service($transaction)->calc(), 0.01);

            $output_row = $output_rows->fetchOne($key);
            $tax = reset($output_row);

            $this->assertEquals($result_tax, $tax);
        }
    }
}
