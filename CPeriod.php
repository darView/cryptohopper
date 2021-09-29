<?php

/**
 * Class CPeriod
 */
class CPeriod
{
    // periods available
    const PERIOD_1M = '1m';
    const PERIOD_5M = '5m';
    const PERIOD_15M = '15m';
    const PERIOD_30M = '30m';
    const PERIOD_1H = '1h';
    const PERIOD_2H = '2h';
    const PERIOD_4H = '4h';
    const PERIOD_1D = '1d';

    /**
     * @param string $period
     * @return int
     * @throws Exception
     */
    public static function getPeriodInSeconds(string $period)
    {
        switch ($period) {
            case self::PERIOD_1M:
                return 60;
            case self::PERIOD_5M:
                return 300;
            case self::PERIOD_15M:
                return 900;
            case self::PERIOD_30M:
                return 1800;
            case self::PERIOD_1H:
                return 3600;
            case self::PERIOD_2H:
                return 7200;
            case self::PERIOD_4H:
                return 14400;
            case self::PERIOD_1D:
                return 86400;
            default:
                throw new Exception("Invalid period.", 400);
        }
    }
}
