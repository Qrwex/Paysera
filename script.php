#!/usr/bin/php
<?php

use League\Csv\Reader;
use App\Transactions\TransactionFactory;
use App\Currencies\Currency;
use App\Reports\ReportFactory;

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

    $report = ReportFactory::get($reader);

    $report->dump();

} catch (Exception $e) {
    exit($e->getMessage());
}


//$argv; $argc;
