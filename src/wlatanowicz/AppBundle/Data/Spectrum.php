<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class Spectrum
{
    /**
     * @var SpectrumPoint[]
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
     * Spectrum constructor.
     * @param SpectrumPoint[] $dataPoints
     */
    public function __construct(array $dataPoints = [])
    {
        $this->dataPoints = $dataPoints;
        $this->frequencyRange = null;
        $this->powerRange = null;
    }

    /**
     * @return SpectrumPoint[]
     */
    public function getDataPoints(): array
    {
        return $this->dataPoints;
    }

    public function getFrequencyRange(): Range
    {
        if ($this->frequencyRange === null) {
            $range = null;
            foreach ($this->dataPoints as $dataPoint) {
                $range = Range::fromRanges(
                    $range,
                    new Range(
                        $dataPoint->getFrequency(),
                        $dataPoint->getFrequency()
                    )
                );
            }
            $this->frequencyRange = $range;
        }
        return $this->frequencyRange;
    }

    public function getPowerRange(): Range
    {
        if ($this->powerRange === null) {
            $range = null;
            foreach ($this->dataPoints as $dataPoint) {
                $range = Range::fromRanges(
                    $range,
                    new Range(
                        $dataPoint->getPower(),
                        $dataPoint->getPower()
                    )
                );
            }
            $this->powerRange = $range;
        }
        return $this->powerRange;
    }
}
