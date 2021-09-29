<?php

/**
 * Class CMaXingStrategy (Moving Average Crossing)
 */
class CMaXingStrategy implements IStrategy

{
    /** @var  IMa */
    protected $ma;

    /** @var  int */
    protected $fast;

    /** @var  int */
    protected $slow;

    /**
     * CMaXingStrategy constructor.
     * @param IMa $ma
     * @param int $fast
     * @param int $slow
     */
    public function __construct(IMa $ma, int $fast, int $slow)
    {
        $this->setMa($ma);
        $this->setFast($fast);
        $this->setSlow($slow);
    }

    /**
     * @return array
     */
    public function apply(): array
    {
        // default result
        $result = ['signal' => 'neutral'];

        // calculate indicator values
        $maCur['slow'] = $this->getMa()->calculate($this->getSlow());
        $maCur['fast'] = $this->getMa()->calculate($this->getFast());
        $maPrev['slow'] = $this->getMa()->calculate($this->getSlow(), -1);
        $maPrev['fast'] = $this->getMa()->calculate($this->getFast(), -1);

        // actual strategy
        if (($maCur['fast'] < $maCur['slow']) && ($maPrev['fast'] >= $maPrev['slow'])) {
            $result['signal'] = 'sell';
        } elseif (($maCur['fast'] > $maCur['slow']) && ($maPrev['fast'] <= $maPrev['slow'])) {
            $result['signal'] = 'buy';
        }

        return $result;
    }

    /**
     * @return IMa
     */
    public function getMa(): IMa
    {
        return $this->ma;
    }

    /**
     * @param IMa $ma
     */
    public function setMa(IMa $ma)
    {
        $this->ma = $ma;
    }

    /**
     * @return int
     */
    public function getFast(): int
    {
        return $this->fast;
    }

    /**
     * @param int $fast
     * @throws Exception
     */
    public function setFast(int $fast)
    {
        if (isset($this->slow) && $fast >= $this->slow) {
            throw new Exception("Fast MA must be faster then slow MA.");
        }
        $this->fast = $fast;
    }

    /**
     * @return int
     */
    public function getSlow(): int
    {
        return $this->slow;
    }

    /**
     * @param int $slow
     * @throws Exception
     */
    public function setSlow(int $slow)
    {
        if (isset($this->fast) && $slow <= $this->fast) {
            throw new Exception("Slow MA must be slower then fast MA.");
        }
        $this->slow = $slow;
    }
}