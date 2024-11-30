<?php

require 'vendor/autoload.php';

use App\App;

try {
    $inputFile = $argv[1] ?? null;
    $app = new App();
    $app->run($inputFile);
} catch (Exception $e) {
    echo 'Application failed: ' . $e->getMessage() . PHP_EOL;
}