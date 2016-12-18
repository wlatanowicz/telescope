<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class RangedValue
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var Range
     */
    private $range;

    /**
     * RangedValue constructor.
     * @param float $value
     * @param Range $range
     */
    public function __construct(float $value, Range $range)
    {
        $this->value = $value;
        $this->range = $range;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return Range
     */
    public function getRange(): Range
    {
        return $this->range;
    }

    public function inRange(Range $targetRange): self
    {
        $scale = ($targetRange->getMax() - $targetRange->getMin()) / ($this->getRange()->getMax() - $this->getRange()->getMin());
        $zeroed = $this->getValue() - $this->getRange()->getMin();
        $scaled = $zeroed * $scale;
        $convertedValue = $scaled + $targetRange->getMin();
        return new self($convertedValue, $targetRange);
    }

    public static function ONE(): self
    {
        return new self(1, new Range(0, 1));
    }

    public static function ZERO(): self
    {
        return new self(0, new Range(0, 1));
    }
}
