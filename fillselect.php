<?php

// Встановлення та завантаження Guzzle HTTP Client
require 'vendor/autoload.php';
use GuzzleHttp\Client;

// Створення об'єкту Guzzle HTTP Client
$client = new Client();

// Запит до CoinMarketCap API для отримання списку топ-10 криптовалют
$response = $client->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
    'headers' => [
        'X-CMC_PRO_API_KEY' => '36bed8e2-9312-4358-8fc1-f0c3aab6dd97' // Замініть 'YOUR_API_KEY' на ваш ключ API
    ],
    'query' => [
        'limit' => 10 // Кількість криптовалют, які потрібно отримати
    ]
]);

// Перевірка статусу відповіді
if ($response->getStatusCode() == 200) {

    $data = json_decode($response->getBody(), true);
    $cryptocurrencies = $data['data'];


    // Виведення даних про криптовалюти
    foreach ($cryptocurrencies as $cryptocurrency) {

        $name = $cryptocurrency['symbol'];

        echo("<option value=\"" . $name . "\">" . $name .  "</option>");
        
    }

    
} else {
    echo "Error: " . $response->getStatusCode();
}
?>