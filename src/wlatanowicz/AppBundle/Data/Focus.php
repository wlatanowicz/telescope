<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class Focus
{
    /**
     * @var StarDimensions
     */
    private $starDimensions;

    /**
     * FocusMeasure constructor.
     * @param StarDimensions $starDimensions
     */
    public function __construct(StarDimensions $starDimensions)
    {
        $this->starDimensions = $starDimensions;
    }
}
