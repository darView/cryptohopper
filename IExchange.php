<?php

/**
 * Interface IExchange
 */
interface IExchange
{
    // available exchanges
    const COINBASE_PRO = 'coinbasepro';

    /**
     * @param int $start_unix_ts
     * @param int $end_unix_ts
     * @param string $period
     * @return array
     */
    function getData(int $start_unix_ts, int $end_unix_ts, string $period): array;
}