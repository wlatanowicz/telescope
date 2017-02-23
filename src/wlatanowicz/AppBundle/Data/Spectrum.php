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

    /**
     * @param string[] $lines
     * @return self
     */
    public static function fromReceiverOutput(array $lines): self
    {
        $spectrumPointArrays = [];
        foreach($lines as $line) {
            $spectrumPointArrays[] = self::processInputLine($line);
        }

        $spectrumPoints = array_reduce(
            $spectrumPointArrays,
            "array_merge",
            []
        );
        return new self($spectrumPoints);
    }

    /**
     * @param string $line
     * @return SpectrumPoint[]
     */
    private static function processInputLine(string $line): array
    {
        $cols = explode(",", $line);
        $cols = array_map("trim", $cols);
        list($dateStr, $timeStr, $minFreqStr, $maxFreqStr, $freqStepStr) = $cols;
        $powersStr = array_slice($cols, 6);

        $powers = array_map("floatval", $powersStr);
        $minFreq = floatval($minFreqStr);
        $freqStep = floatval($freqStepStr);

        $spectrumPoints = [];

        foreach ($powers as $i => $power) {
            $freq = $minFreq + $i * $freqStep;
            $spectrumPoints[] = new SpectrumPoint(
                $freq,
                $power);
        }

        return $spectrumPoints;
    }
}
