#!/usr/bin/php
<?php

use League\Csv\Reader;
use App\Transactions\TransactionFactory;
use App\Currencies\Currency;

/**
 * Register The Composer Auto Loader
 */
require __DIR__ . '/vendor/autoload.php';

$path = 'input.csv';

try {

    if (!file_exists($path)) {
        throw new Exception('File does not exist');
    }

    $reader = Reader::createFromPath($path);

    foreach ($reader as $key => $row) {

        $transaction = TransactionFactory::get($row);

        if (!$transaction) {
            throw new Exception('Bad data');
        }

        $amount = Currency::convert('USD', 'JPY', 500);

        $rounded = rtn($amount, .01);


        dump($rounded);
        dd('aa');
        echo rtn();
        die;
        echo \App\Currencies\Currency::convert('USD', 'JPY', 500);


        var_dump($transaction);
        die;
    }
} catch (Exception $e) {
    exit($e->getMessage());
}


//$argv; $argc;
