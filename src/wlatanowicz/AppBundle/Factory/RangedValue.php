<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue as RangedValueData;

class RangedValue
{
    /**
     * @var Range
     */
    private $targetRange;

    /**
     * RangedValue constructor.
     * @param Range $targetRange
     */
    public function __construct(Range $targetRange)
    {
        $this->targetRange = $targetRange;
    }

    public function convert(RangedValueData $value): RangedValueData
    {
        $scale = ($this->targetRange->getMax() - $this->targetRange->getMin()) / ($value->getRange()->getMax() - $value->getRange()->getMin());
        $zeroed = $value->getValue() - $value->getRange()->getMin();
        $scaled = $zeroed * $scale;
        $convertedValue = $scaled + $this->targetRange->getMin();
        return new RangedValueData($convertedValue, $this->targetRange);
    }
}
