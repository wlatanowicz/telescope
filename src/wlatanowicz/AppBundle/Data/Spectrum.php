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
     * Spectrum constructor.
     * @param SpectrumPoint[] $dataPoints
     */
    public function __construct(array $dataPoints = [])
    {
        $this->dataPoints = $dataPoints;
    }

    /**
     * @return SpectrumPoint[]
     */
    public function getDataPoints(): array
    {
        return $this->dataPoints;
    }
}
