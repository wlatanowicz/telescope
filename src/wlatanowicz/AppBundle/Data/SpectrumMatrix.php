<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class SpectrumMatrix
{
    /**
     * @var Spectrum[][]
     */
    private $spectrums;

    /**
     * SpectrumMatrix constructor.
     */
    public function __construct()
    {
        $this->spectrums = [];
    }

    public function getWidth(): int
    {
        return 1 + max(array_keys($this->spectrums));
    }

    public function getHeight(): int
    {
        return 1 + max(array_map(function($col) {
            return max(array_keys($col));
        }, $this->spectrums));
    }

    public function setSpectrum(int $x, int $y, Spectrum $spectrum)
    {
        if (! isset($this->spectrums[$x])) {
            $this->spectrums[$x] = [];
        }

        $this->spectrums[$x][$y] = $spectrum;
    }

    public function hasSpectrum(int $x, int $y): bool
    {
        return isset($this->spectrums[$x][$y]);
    }

    public function getSpectrum(int $x, int $y): Spectrum
    {
        return $this->spectrums[$x][$y];
    }
}
