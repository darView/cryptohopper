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
    function getData($start_unix_ts, $end_unix_ts, $period): array;
}