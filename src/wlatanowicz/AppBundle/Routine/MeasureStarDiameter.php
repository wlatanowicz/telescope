<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\StarDimensions;

class MeasureStarDiameter
{
    /**
     * @var float
     */
    private $threshold;

    /**
     * MeasureStarDimensions constructor.
     * @param $threshold
     */
    public function __construct(float $threshold)
    {
        $this->threshold = $threshold;
    }

    public function measure(GdImage $image): float
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $range = Range::ONE();

        $area = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $this->threshold) {
                    $area++;
                }
            }
        }

        return sqrt( $area / M_PI );
    }
}
