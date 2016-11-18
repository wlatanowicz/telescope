<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Range as RangeData;

class Range
{
    public static function extend(RangeData $range1 = null, RangeData $range2 = null): RangeData
    {
        if ($range1 == null && $range2 !== null) {
            return clone $range2;
        }
        if ($range2 == null && $range1 !== null) {
            return clone $range1;
        }

        return new RangeData(
            min($range1->getMin(), $range2->getMin()),
            max($range1->getMax(), $range2->getMax())
        );
    }
}
