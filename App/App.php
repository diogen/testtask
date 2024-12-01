<?php

namespace App;

class App
{
    private CommissionCalculator $calculator;

    public function __construct(CommissionCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

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
        if(!file_exists($inputFile)) {
            throw new \InvalidArgumentException('Input file is missing!');
        }

        $data = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_map('json_decode', $data);
    }

    private function processTransactions(array $transactions): array
    {
        return $this->calculator->calculate($transactions);
    }

    private function outputResults(array $results): void
    {

    }
}