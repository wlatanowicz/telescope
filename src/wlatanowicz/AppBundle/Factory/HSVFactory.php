<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\HSV;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue;
use wlatanowicz\AppBundle\Data\SpectrumPoint;

class HSVFactory
{
    public function fromSpectrumPoint(SpectrumPoint $spectrumPoint, Range $frequencyRange, Range $powerRange): HSV
    {
        $targetRange = new Range(0, 280);
        $frequency = new RangedValue($spectrumPoint->getFrequency(), $frequencyRange);

        $hValue = $frequency->inRange($targetRange)->getValue();

        $h = new RangedValue($hValue, Range::DEGREE());
        $s = RangedValue::ONE();
        $v = new RangedValue($spectrumPoint->getPower(), $powerRange);

        return new HSV($h, $s, $v );
    }
}
