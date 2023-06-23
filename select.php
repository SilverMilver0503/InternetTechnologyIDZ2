
<?php

// Встановлення та завантаження Guzzle HTTP Client
require 'vendor/autoload.php';
use GuzzleHttp\Client;

// Створення об'єкту Guzzle HTTP Client
$client = new Client();

// Запит до CoinMarketCap API для отримання списку топ-10 криптовалют
$responseOne = $client->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/info?', [
    'headers' => [
        'X-CMC_PRO_API_KEY' => '36bed8e2-9312-4358-8fc1-f0c3aab6dd97'
    ],
    'query' => [
        'symbol' => $_POST['name'] // Кількість криптовалют, які потрібно отримати
    ]
]);

$symbol = $_POST['name'];

$data = json_decode($responseOne->getBody(), true);

$info = $data['data'][$symbol];

$name = $info['name'];
$offsite =  $info['urls']['website'][0];
$description =  $info['description'];
$logo =  $info['logo'];

$response = $client->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
    'headers' => [
        'X-CMC_PRO_API_KEY' => '36bed8e2-9312-4358-8fc1-f0c3aab6dd97'
    ],
    'query' => [
        'start' => 1, // Початковий індекс для списку криптовалют
        'limit' => 10 // Кількість криптовалют, для яких отримуємо дані
    ]
]);

$data = json_decode($response->getBody(), true);
$cryptocurrencies = $data['data'];

foreach ($cryptocurrencies as $cryptocurrency) {
    if($cryptocurrency['symbol'] == $symbol){

        $change24h = $cryptocurrency['quote']['USD']['percent_change_24h'];

    //    echo "Symbol: $symbol, 24h Change: $change24h%\n";
    
        $change = $change24h;
    }
}

//$change = $info['quote']['USD']['percent_change_24h'];

$convertResponse = $client->get('https://pro-api.coinmarketcap.com/v1/tools/price-conversion', [
    'headers' => [
        'X-CMC_PRO_API_KEY' => '36bed8e2-9312-4358-8fc1-f0c3aab6dd97'
    ],
    'query' => [
        'id' => $info['id'],
        'amount' => 1, // Specify the amount of cryptocurrency for conversion
        'convert' => 'UAH' // Specify the desired currency code (UAH for Ukrainian hryvnia)
    ]
]);

$convertData = json_decode($convertResponse->getBody(), true);
$priceInUAH = $convertData['data']['quote']['UAH']['price'];

echo("<h1>");
echo($name);
echo("<br>");
echo($symbol);
echo("</h1>");

echo("<p>");
echo("<b>Офіційний сайт та опис:</b>");
echo("<br>");
echo($offsite);
echo("<br>");
echo($description);
echo("<br>");
echo("</p>");
echo("<image alt=");
echo($logo);
echo("< src=");
echo($logo);
echo("></image>");
echo("<p>");
echo("<br>");
echo("<b>Ціна в гривнях зараз:</b>");
echo($priceInUAH);
echo("<br>");
echo("<b>Зміни за останню добу:</b>");
echo($change . "%");
echo("<br>");
echo("</p>");

?>