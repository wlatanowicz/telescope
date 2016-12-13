<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class StarDimensions
{
    /**
     * @var float
     */
    private $height;

    /**
     * @var float
     */
    private $width;

    /**
     * StarDimensions constructor.
     * @param float $width
     * @param float $height
     */
    public function __construct($width, $height)
    {
        $this->height = $height;
        $this->width = $width;
    }

    public static function fromGdImage(GdImage $image, float $threshold): self
    {
        return new self(
            self::measureStarWidth($image, $threshold),
            self::measureStarHeight($image, $threshold)
        );
    }

    private static function measureStarWidth(GdImage $image, float $threshold): float
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $range = Range::ONE();

        $starWidth = null;

        for ($y = 0; $y < $height; $y++) {
            $left = null;
            $right = null;
            for ($x = 0; $x < $width && $left === null; $x++) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $threshold) {
                    $left = $x;
                }
            }
            for ($x = $width-1; $x >= 0 && $right === null; $x--) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $threshold) {
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

    private static function measureStarHeight(GdImage $image, float $threshold): float
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $range = Range::ONE();

        $starHeight = null;

        for ($x = 0; $x < $width; $x++) {
            $top = null;
            $bottom = null;
            for ($y = 0; $y < $height && $bottom === null; $y++) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $threshold) {
                    $bottom = $y;
                }
            }
            for ($y = $height-1; $y >= 0 && $top === null; $y--) {
                if ($image->getBrightness($x, $y)->inRange($range)->getValue() > $threshold) {
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
