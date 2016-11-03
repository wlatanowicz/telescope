<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class DataPoint
{
    /**
     * @var Coordinates
     */
    private $coordinates;

    /**
     * @var Spectrum
     */
    private $spectrum;

    /**
     * DataPoint constructor.
     * @param Coordinates $coordinates
     * @param Spectrum $spectrum
     */
    public function __construct(Coordinates $coordinates, Spectrum $spectrum)
    {
        $this->coordinates = $coordinates;
        $this->spectrum = $spectrum;
    }

    /**
     * @return Coordinates
     */
    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    /**
     * @return RGB
     */
    public function getColor(): RGB
    {
        //@TODO
    }

    /**
     * @return Spectrum
     */
    private function getSpectrum(): Spectrum
    {
        return $this->spectrum;
    }
}
