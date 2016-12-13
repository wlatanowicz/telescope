<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue;
use wlatanowicz\AppBundle\Data\HSV as HSVData;
use wlatanowicz\AppBundle\Data\SpectrumPoint;

class HSV
{
    /**
     * @var Range
     */
    private $frequencyRange;

    /**
     * @var Range
     */
    private $powerRange;

    public function __construct(Range $frequencyRange, Range $powerRange)
    {
        $this->frequencyRange = $frequencyRange;
        $this->powerRange = $powerRange;
    }

    public function fromSpectrumPoint(SpectrumPoint $spectrumPoint): HSVData
    {
        $targetRange = new Range(0, 280);
        $frequency = new RangedValue($spectrumPoint->getFrequency(), $this->frequencyRange);

        $hValue = $frequency->inRange($targetRange)->getValue();

        $h = new RangedValue($hValue, Range::DEGREE());
        $s = RangedValue::ONE();
        $v = new RangedValue($spectrumPoint->getPower(), $this->powerRange);

        new HSVData($h, $s, $v );
    }
}
