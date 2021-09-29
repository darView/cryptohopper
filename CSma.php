<?php

/**
 * Class CSma
 */
class CSma implements IMa
{
    /** @var  float[] */
    protected $prices;

    /**
     * CSma constructor.
     * @param float[] $prices
     */
    public function __construct(array $prices)
    {
        $this->setPrices($prices);
    }

    /**
     * @param int $length
     * @param int $offset
     * @return float
     */
    public function calculate(int $length, int $offset = 0): float
    {
        $prices = array_slice($this->getPrices(), -($length - $offset), $length);

        return (array_sum($prices) / $length);
    }

    /**
     * @return float[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param float[] $prices
     */
    public function setPrices(array $prices)
    {
        $this->prices = $prices;
    }
}
