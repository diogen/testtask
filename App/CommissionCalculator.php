<?php

namespace App;

use GuzzleHttp\Client;
use InvalidArgumentException;

class CommissionCalculator
{
    private const EU_COMMISSION_RATE = 0.01;
    private const NON_EU_COMMISSION_RATE = 0.02;

    private Client $httpClient;
    private CountryChecker $countryChecker;

    private string $binServiceUrl;
    private string $binServiceApiKey;
    private string $exchangeRateServiceUrl;

    public function __construct(Client $httpClient, CountryChecker $countryChecker)
    {
        $this->httpClient = $httpClient;
        $this->countryChecker = $countryChecker;

        $this->binServiceUrl = $_ENV['BIN_SERVICE_URL'] ??
            throw new InvalidArgumentException('BIN_SERVICE_URL not defined in .env');
        $this->binServiceApiKey = $_ENV['BIN_SERVICE_API_KEY'] ??
            throw new InvalidArgumentException('BIN_SERVICE_API_KEY not defined in .env');
        $this->exchangeRateServiceUrl = $_ENV['EXCHANGE_RATE_SERVICE_URL'] ??
            throw new InvalidArgumentException('EXCHANGE_RATE_SERVICE_URL not defined in .env');
    }

    public function calculate(array $transactions): array
    {
        $results = [];

        foreach ($transactions as $transaction) {
            try {
                $rate = $this->getExchangeRate($transaction->currency);
                $binData = $this->getBinData($transaction->bin);
                var_dump($this->countryChecker->isEU(strtoupper($binData['data']['country']['code'])));die;
                $isEU = $this->countryChecker->isEU($binData['data']['country']['code'] ?? '');
                $commission = $this->calculateCommission($transaction->amount, $rate, $isEU);
                echo $transaction->amount. ' ' .$rate. ' ' .(int)$isEU.PHP_EOL;
echo $commission . PHP_EOL;
                $results[] = [
                    'transaction' => $transaction,
                    'commission' => $commission
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'transaction' => $transaction,
                    'error' => $e->getMessage()
                ];
            }
        }
        return $results;
    }

    private function calculateCommission(float $amount, float $rate, bool $isEU): float
    {
        $amountInEur = $amount / $rate;
        $commission_rate = $isEU ? self::EU_COMMISSION_RATE : self::NON_EU_COMMISSION_RATE;

        return ceil($amountInEur * $commission_rate * 100) / 100;
    }

    private function getExchangeRate(string $currency): float
    {
        if ($currency === 'EUR') {
            return 1.0;
        }

        $response = $this->httpClient->get($this->exchangeRateServiceUrl);
        $rates = json_decode($response->getBody(), true)['rates'];
        if(!isset($rates[$currency])) {
            throw new \Exception('Currency rate for ' . $currency . ' not found!');
        }
        return $rates[$currency];
    }

    private function getBinData(string $bin): array
    {
        $binURL = $this->binServiceUrl . mb_substr($bin, 0, 6) . '?api_key=' . $this->binServiceApiKey;

        try {
            $response = $this->httpClient->get($this->exchangeRateServiceUrl);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            // Check if the response is as expected
            if ($statusCode === 200) {
                $data = json_decode($body, true);
                if (isset($data['data'])) {
                    return $data;
                } else {
                    throw new \Exception("Unexpected response structure.");
                }
            } else {
                throw new \Exception("API returned an unexpected status code: $statusCode");
            }
        } catch (GuzzleHttp\Exception\RequestException $e) {
            echo "HTTP Request failed: " . $e->getMessage();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}