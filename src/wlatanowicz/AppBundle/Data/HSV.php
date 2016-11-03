<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class HSV
{
    /**
     * @var float
     */
    private $h;

    /**
     * @var float
     */
    private $s;

    /**
     * @var float
     */
    private $v;

    /**
     * HSV constructor.
     * @param float $h 0..259
     * @param float $s 0..1
     * @param float $v 0..1
     */
    public function __construct(float $h, float $s, float $v)
    {
        $this->h = $h;
        $this->s = $s;
        $this->v = $v;
    }

    /**
     * @return float
     */
    public function getH(): float
    {
        return $this->h;
    }

    /**
     * @return float
     */
    public function getS(): float
    {
        return $this->s;
    }

    /**
     * @return float
     */
    public function getV(): float
    {
        return $this->v;
    }
}