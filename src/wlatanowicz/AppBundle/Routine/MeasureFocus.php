<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\Focus;
use wlatanowicz\AppBundle\Data\ImagickImage;

class MeasureFocus
{
    /**
     * @var MeasureStarDimensions
     */
    private $measureStarDimensions;

    /**
     * MeasureFocus constructor.
     * @param MeasureStarDimensions $measureStarDimensions
     */
    public function __construct(MeasureStarDimensions $measureStarDimensions)
    {
        $this->measureStarDimensions = $measureStarDimensions;
    }

    public function measure(ImagickImage $image): Focus
    {
        return new Focus(
            $this->measureStarDimensions->measure($image)
        );
    }
}
