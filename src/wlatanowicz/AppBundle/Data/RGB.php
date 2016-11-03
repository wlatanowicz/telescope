<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class RGB
{
    /**
     * @var float
     */
    private $r;

    /**
     * @var float
     */
    private $g;

    /**
     * @var float
     */
    private $b;

    /**
     * RGB constructor.
     * @param float $r 0..1
     * @param float $g 0..1
     * @param float $b 0..1
     */
    public function __construct(float $r, float $g, float $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    /**
     * @return float
     */
    public function getR(): float
    {
        return $this->r;
    }

    /**
     * @return float
     */
    public function getG(): float
    {
        return $this->g;
    }

    /**
     * @return float
     */
    public function getB(): float
    {
        return $this->b;
    }
}
