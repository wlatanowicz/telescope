<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class RGB
{
    /**
     * @var RangedValue
     */
    private $r;

    /**
     * @var RangedValue
     */
    private $g;

    /**
     * @var RangedValue
     */
    private $b;

    /**
     * RGB constructor.
     * @param RangedValue $r
     * @param RangedValue $g
     * @param RangedValue $b
     */
    public function __construct(RangedValue $r, RangedValue $g, RangedValue $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    /**
     * @return RangedValue
     */
    public function getR(): RangedValue
    {
        return $this->r;
    }

    /**
     * @return RangedValue
     */
    public function getG(): RangedValue
    {
        return $this->g;
    }

    /**
     * @return RangedValue
     */
    public function getB(): RangedValue
    {
        return $this->b;
    }
}
