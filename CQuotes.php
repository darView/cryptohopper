<?php

/**
 * Class CQuotes
 */
class CQuotes
{
    /** @var  IExchange */
    protected $exchangeHandler;

    /** @var  string */
    protected $period;

    /** @var  int */
    protected $length;

    /**
     * CQuotes constructor.
     * @param IExchange $exchangeHandler
     * @param string $period
     * @param int $length
     */
    public function __construct(IExchange $exchangeHandler, string $period, int $length)
    {
        $this->setExchangeHandler($exchangeHandler);
        $this->setPeriod($period);
        $this->setLength($length);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getQuotes(): array
    {
        $endUnixTs = time();
        $startUnixTs = $this->calculateStartUnixTs($endUnixTs, $this->getLength());

        $quotes = $this->getExchangeHandler()->getData($startUnixTs, $endUnixTs, $this->getPeriod());

        if (array_key_exists('error', $quotes)) {
            // cannot distinguish between 400 and 500 because endpoint always returns 200
            throw new Exception($quotes['error'], 400);
        }

        if (count($quotes) < $this->getLength()) {
            throw new Exception("Not enough quotes to calculate SMA.", 500);
        }

        return $quotes;
    }

    /**
     * @param int $unixTs
     * @param int $length
     * @return int
     */
    protected function calculateStartUnixTs(int $unixTs, int $length): int
    {
        $timeDiff = $length * CPeriod::getPeriodInSeconds($this->getPeriod());
        return ($unixTs - $timeDiff);
    }

    /**
     * @return IExchange
     */
    public function getExchangeHandler(): IExchange
    {
        return $this->exchangeHandler;
    }

    /**
     * @param IExchange $exchangeHandler
     */
    public function setExchangeHandler(IExchange $exchangeHandler)
    {
        $this->exchangeHandler = $exchangeHandler;
    }

    /**
     * @return string
     */
    public function getPeriod(): string
    {
        return $this->period;
    }


    /**
     * @param string $period
     */
    public function setPeriod(string $period)
    {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length)
    {
        $this->length = $length;
    }
}