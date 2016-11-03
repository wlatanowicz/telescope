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
            $minFrequency = null;
            $maxFrequency = null;
            foreach ($this->dataPoints as $dataPoint) {
                $minFrequency
                    = $minFrequency === null || $dataPoint->getFrequency() < $minFrequency
                    ? $dataPoint->getFrequency()
                    : $minFrequency;
                $maxFrequency
                    = $maxFrequency === null || $dataPoint->getFrequency() > $maxFrequency
                    ? $dataPoint->getFrequency()
                    : $maxFrequency;
            }
            $this->frequencyRange = new Range($minFrequency, $maxFrequency);
        }
        return $this->frequencyRange;
    }

    public function getPowerRange(): Range
    {
        if ($this->powerRange === null) {
            $minPower = null;
            $maxPower = null;
            foreach ($this->dataPoints as $dataPoint) {
                $minPower
                    = $minPower === null || $dataPoint->getPower() < $minPower
                    ? $dataPoint->getPower()
                    : $minPower;
                $maxPower
                    = $maxPower === null || $dataPoint->getPower() > $maxPower
                    ? $dataPoint->getPower()
                    : $maxPower;
            }
            $this->powerRange = new Range($minPower, $maxPower);
        }
        return $this->powerRange;
    }
}
