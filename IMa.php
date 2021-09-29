<?php

/**
 * Interface IMa (Moving Average)
 */
interface IMa
{
    /**
     * @param int $length
     * @param int $offset
     * @return float
     */
    function calculate(int $length, int $offset = 0): float;
}