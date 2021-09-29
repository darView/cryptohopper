<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Europe/Amsterdam");

/**
 * Autoload classes
 */
spl_autoload_register(function ($className) {
    include "{$className}.php";
});

/**
 * @param string $exchange
 * @param string $market
 * @param string $period
 */
function main(string $exchange, string $market, string $period)
{
    $fast = 8;
    $slow = 55;

    $response_code = 200;
    try {
        // get closings
        $exchangeHandler = CExchangeFactory::createExchangeHandler($exchange, $market);
        // slow period + 1 closing price needed to calculate previous MA
        $quotesHandler = new CQuotes($exchangeHandler, $period, ($slow + 1));
        $quotes = $quotesHandler->getQuotes();
        $closings = array_column($quotes, 'Close');

        // execute moving average crossing strategy using Simple Moving Averages (SMA)
        $ma = new CSma($closings);
        $strategy = new CMaXingStrategy($ma, $fast, $slow);
        $result = $strategy->apply();
    } catch (Exception $e) {
        $response_code = $e->getCode();
        $result = ['error' => $e->getMessage()];
    }

    header('Content-Type: application/json; charset=utf-8');
    switch ($response_code) {
        case 200:
            header("HTTP/1.1 200 OK");
            break;
        case 400:
            header("HTTP/1.1 400 Bad Request");
            break;
        case 500:
            header('HTTP/1.1 500 Internal Server Error');
            break;
    }
    if (array_key_exists('error', $result)) {

    } else {
    }
    echo json_encode($result);
}

$exchange = (!empty($_GET['exchange'])) ? strtolower($_GET['exchange']) : IExchange::COINBASE_PRO;
$market = (!empty($_GET['market'])) ? $_GET['market'] : 'BTC-EUR';
$period = (!empty($_GET['period'])) ? strtolower($_GET['period']) : CPeriod::PERIOD_1H;

main($exchange, $market, $period);
