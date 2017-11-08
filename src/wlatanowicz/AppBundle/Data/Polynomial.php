<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Data;

class Polynomial
{
    /**
     * @var float[]
     */
    private $coefficients;

    /**
     * Polynomial constructor.
     * @param \float[] $coefficients
     */
    public function __construct(array $coefficients)
    {
        $this->coefficients = $coefficients;
    }

    public function getCoefficient(int $index): float
    {
        return $this->coefficients[$index];
    }
}
