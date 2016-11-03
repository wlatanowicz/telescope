<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class SpectrumPoint
{
    /**
     * @var float
     */
    private $frequency;

    /**
     * @var float
     */
    private $power;

    /**
     * DataPoint constructor.
     * @param float $frequency
     * @param $power
     */
    public function __construct(float $frequency, float $power)
    {
        $this->frequency = $frequency;
        $this->power = $power;
    }


}
