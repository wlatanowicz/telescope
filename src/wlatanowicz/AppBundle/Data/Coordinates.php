<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class Coordinates
{
    /**
     * @var float
     */
    private $rightAscension;

    /**
     * @var float;
     */
    private $declination;

    /**
     * Coordinates constructor.
     * @param float $rightAscension
     * @param float $declination
     */
    public function __construct(float $rightAscension, float $declination)
    {
        $this->rightAscension = $rightAscension;
        $this->declination = $declination;
    }

    /**
     * @return float
     */
    public function getRightAscension(): float
    {
        return $this->rightAscension;
    }

    /**
     * @return float
     */
    public function getDeclination(): float
    {
        return $this->declination;
    }
}