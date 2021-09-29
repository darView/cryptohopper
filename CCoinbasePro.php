<?php

/**
 * Class CChart
 */
class CCoinbasePro implements IExchange
{

    /** @var  string */
    protected $market;

    /**
     * CCoinbasePro constructor.
     * @param string $market
     */
    public function __construct(string $market)
    {
        $this->setMarket($market);
    }

    /**
     * @param int $start_unix_ts
     * @param int $end_unix_ts
     * @param string $period
     * @return array
     */
    public function getData(int $start_unix_ts, int $end_unix_ts, string $period): array
    {
        $chart = $this->getChart($start_unix_ts, $end_unix_ts, $period);

        // replace latest value by most acurate one
        $latestClose = $this->getLatestClose();
        $latestQuote = array_pop($chart);
        $latestQuote['Close'] = $latestClose;
        array_push($chart, $latestQuote);

        return $chart;
    }

    /**
     * @param int $start_unix_ts
     * @param int $end_unix_ts
     * @param string $period
     * @return array
     */
    protected function getChart(int $start_unix_ts, int $end_unix_ts, string $period): array
    {
        $headers[] = 'Content-Type: application/json';

        $url = sprintf(
            "http://cryptohopper-ticker-frontend.us-east-1.elasticbeanstalk.com/v1/coinbasepro/candles?pair=%s&start=%u&end=%u&period=%s",
            urlencode($this->getMarket()),
            $start_unix_ts,
            $end_unix_ts,
            urlencode($period));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }

    /**
     * @return float
     * @throws Exception
     */
    protected function getLatestClose(): float
    {
        $tickerPrices = $this->getTickerPrices();
        if (!array_key_exists('data', $tickerPrices) || !array_key_exists($this->getMarket(), $tickerPrices['data'])) {
            throw new Exception("Market not found.", 500);
        }
        return (float)$tickerPrices['data'][$this->getMarket()]['last'];
    }

    protected function getTickerPrices()
    {
        $headers[] = 'Content-Type: application/json';

        $url = "http://cryptohopper-ticker-frontend.us-east-1.elasticbeanstalk.com/v1/coinbasepro/ticker";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }

    /**
     * @return string
     */
    public function getMarket(): string
    {
        return $this->market;
    }

    /**
     * @param string $market
     */
    public function setMarket(string $market)
    {
        $this->market = strtoupper($market);
    }
}
