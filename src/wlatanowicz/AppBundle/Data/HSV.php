<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class HSV
{
    /**
     * @var RangedValue
     */
    private $h;

    /**
     * @var RangedValue
     */
    private $s;

    /**
     * @var RangedValue
     */
    private $v;

    /**
     * HSV constructor.
     * @param RangedValue $h
     * @param RangedValue $s
     * @param RangedValue $v
     */
    public function __construct(RangedValue $h, RangedValue $s, RangedValue $v)
    {
        $this->h = $h;
        $this->s = $s;
        $this->v = $v;
    }

    /**
     * @return RangedValue
     */
    public function getH(): RangedValue
    {
        return $this->h;
    }

    /**
     * @return RangedValue
     */
    public function getS(): RangedValue
    {
        return $this->s;
    }

    /**
     * @return RangedValue
     */
    public function getV(): RangedValue
    {
        return $this->v;
    }
}