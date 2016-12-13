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

    public function map(float $value, self $valuesRange): float
    {
        //@TODO
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

    public static function ONE(): self
    {
        return new self(0, 1);
    }

    public static function DEGREE(): self
    {
        return new self(0, 359.99999999);
    }

    public static function fromRanges(self $range1 = null, self $range2 = null): self
    {
        if ($range1 == null && $range2 !== null) {
            return clone $range2;
        }
        if ($range2 == null && $range1 !== null) {
            return clone $range1;
        }

        return new self(
            min($range1->getMin(), $range2->getMin()),
            max($range1->getMax(), $range2->getMax())
        );
    }
}
