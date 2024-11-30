<?php

namespace App;

class App
{
    public function run(string $inputFile): void
    {
        try {
            $transactions = $this->readInput($inputFile);

            $results = $this->processTransactions($transactions);

            $this->outputResults($results);
        } catch (\Exception $e) {
            echo 'Fatal error: ' . $e->getMessage() . PHP_EOL;
        }
    }

    private function readInput(string $inputFile): array
    {

    }

    private function processTransactions(array $transactions): array
    {

    }

    private function outputResults(array $results): void
    {

    }
}