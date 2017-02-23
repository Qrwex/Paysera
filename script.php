#!/usr/bin/php
<?php

/**
 * Register The Composer Auto Loader
 */
require __DIR__.'/vendor/autoload.php';

use League\Csv\Reader;

$path = 'input.csv';

try {

    if (!file_exists($path))
    {
        throw new Exception('File does not exist');
    }

    $reader = Reader::createFromPath($path);

    foreach ($reader as $key => $row)
    {
        $transaction = \App\Transactions\TransactionFactory::get($row);

        if (!$transaction)
        {
            throw new Exception('Bad data');
        }

        echo \App\Currencies\Currency::convert('USD', 'JPY', 500); die;


        var_dump($transaction); die;
    }
} catch (Exception $e) {
    exit($e->getMessage());
}



//$argv; $argc;