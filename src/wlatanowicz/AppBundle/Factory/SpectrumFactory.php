<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Spectrum;
use wlatanowicz\AppBundle\Data\SpectrumPoint;

class SpectrumFactory
{
    /**
     * @param string[] $lines
     * @return Spectrum
     */
    public function fromReceiverOutput(array $lines): Spectrum
    {
        $spectrumPointArrays = [];
        foreach($lines as $line) {
            $spectrumPointArrays[] = $this->processInputLine($line);
        }

        $spectrumPoints = array_reduce(
            $spectrumPointArrays,
            "array_merge",
            []
        );
        return new Spectrum($spectrumPoints);
    }

    /**
     * @param string $line
     * @return SpectrumPoint[]
     */
    private function processInputLine(string $line): array
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
