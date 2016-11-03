<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class Range
{
    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /**
     * Range constructor.
     * @param float $min
     * @param float $max
     */
    public function __construct(float $min, float $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }
}
