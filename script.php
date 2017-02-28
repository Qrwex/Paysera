#!/usr/bin/php
<?php

use League\Csv\Reader;
use App\Reports\ReportFactory;

/**
 * Register The Composer Auto Loader
 */
require __DIR__ . '/vendor/autoload.php';

try {

    if ($argc < 2) {
        throw new Exception('Missing input');
    }

    $path = $argv[1];

    if (!file_exists($path)) {
        throw new Exception('File does not exist');
    }

    $reader = Reader::createFromPath($path);

    $report = ReportFactory::get($reader);

    $report->dump();

} catch (Exception $e) {
    dd('ERROR: ' . $e->getMessage());
}
