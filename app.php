<?php

require 'vendor/autoload.php';

use App\App;
use App\CommissionCalculator;

try {
    $calculator = new CommissionCalculator();

    $inputFile = $argv[1] ?? null;
    $app = new App($calculator);
    $app->run($inputFile);
} catch (Exception $e) {
    echo 'Application failed: ' . $e->getMessage() . PHP_EOL;
}