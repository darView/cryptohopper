<?php

require_once 'CCoinbasePro.php';

/**
 * Class CExchangeFactory
 */
class CExchangeFactory
{
    /**
     * @param string $exchange
     * @param string $market
     * @return IExchange
     * @throws Exception
     */
    public static function createExchangeHandler(string $exchange, string $market)
    {
        switch ($exchange) {
            case IExchange::COINBASE_PRO:
                return new CCoinbasePro($market);
            default:
                throw new Exception('Unknown exchange.', 400);
        }

    }
}