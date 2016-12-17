<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\StarDimensions;

class MeasureStarDimensions
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

    public function measure(GdImage $image): StarDimensions
    {
        return new StarDimensions(
            $this->measureStarWidth($image),
            $this->measureStarHeight($image)
        );
    }

    private function measureStarWidth(GdImage $image): float
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $range = Range::ONE();

        $starWidth = null;

        for ($y = 0; $y < $height; $y++) {
            $left = null;
            $right = null;
            for ($x = 0; $x < $width && $left === null; $x++) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $this->threshold) {
                    $left = $x;
                }
            }
            for ($x = $width-1; $x >= 0 && $right === null; $x--) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $this->threshold) {
                    $right = $x;
                }
            }

            if ($left !== null && $right !== null)
            {
                $localWidth = 1 + $right - $left;
                $starWidth = $starWidth === null || $localWidth > $starWidth
                    ? $localWidth
                    : $starWidth;
            }
        }

        return $starWidth ?? $width;
    }

    private function measureStarHeight(GdImage $image): float
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $range = Range::ONE();

        $starHeight = null;

        for ($x = 0; $x < $width; $x++) {
            $top = null;
            $bottom = null;
            for ($y = 0; $y < $height && $bottom === null; $y++) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $this->threshold) {
                    $bottom = $y;
                }
            }
            for ($y = $height-1; $y >= 0 && $top === null; $y--) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $this->threshold) {
                    $top = $y;
                }
            }

            if ($bottom !== null && $top !== null)
            {
                $localHeight = 1 + $top - $bottom;
                $starHeight = $starHeight === null || $localHeight > $starHeight
                    ? $localHeight
                    : $starHeight;
            }
        }

        return $starHeight ?? $height;
    }
}
