<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Data\Range;

class MeasureStarFWHM implements MeasureInterface
{
    /**
     * @var float
     */
    private $threshold;

    /**
     * @var float
     */
    private $half;

    /**
     * MeasureStarDimensions constructor.
     * @param $threshold
     */
    public function __construct(float $threshold, float $half = 0.5)
    {
        $this->threshold = $threshold;
        $this->half = $half;
    }

    public function measure(ImagickImage $image): float
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $range = Range::ONE();

        $area = 0;

        $minValue = null;
        $maxValue = null;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $value = $image->getBrightness($x, $y)->inRange($range)->getValue();
                if ($value > $this->threshold) {
                    $minValue = $minValue === null || $value < $minValue
                        ? $value
                        : $minValue;
                    $maxValue = $maxValue === null || $value > $maxValue
                        ? $value
                        : $maxValue;
                }
            }
        }

        $halfWidthThreshold = $minValue + ($maxValue - $minValue) * $this->half;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $halfWidthThreshold) {
                    $area++;
                }
            }
        }

        return sqrt( $area / M_PI );
    }
}
