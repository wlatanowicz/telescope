<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class DataSet
{
    /**
     * @var DataPoint[]
     */
    private $dataPoints;

    /**
     * @var Range
     */
    private $frequencyRange;

    /**
     * @var Range
     */
    private $powerRange;

    /**
     * DataSet constructor.
     * @param DataPoint[] $dataPoints
     */
    public function __construct(array $dataPoints)
    {
        $this->dataPoints = $dataPoints;
        $this->powerRange = null;
        $this->frequencyRange = null;
    }

    public function getFrequencyRange(): Range
    {
        if ($this->frequencyRange === null) {
            $range = null;
            foreach ($this->dataPoints as $dataPoint) {
                $range = Range::fromRanges($range, $dataPoint->getSpectrum()->getFrequencyRange());
            }
            $this->frequencyRange = $range;
        }
        return $this->frequencyRange;
    }
}
