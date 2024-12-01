<?php

require 'vendor/autoload.php';

use App\App;
use App\CommissionCalculator;
use App\CountryChecker;
use GuzzleHttp\Client;

try {
    // Load environment variables
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/');
    $dotenv->load();

    //dependencies
    $httpClient = new Client();
    $countryChecker = new CountryChecker();
    $calculator = new CommissionCalculator($httpClient, $countryChecker);

    $inputFile = $argv[1] ?? null;
    $app = new App($calculator);
    $app->run($inputFile);
} catch (Exception $e) {
    echo 'Application failed: ' . $e->getMessage() . PHP_EOL;
}