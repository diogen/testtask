<?php

use App\App;

try {
    $inputFile = $argv[1] ?? null;
    $app = new App();
    $app->run();
} catch (Exception $e) {
    echo 'Application failed: ' . $e->getMessage() . PHP_EOL;
}