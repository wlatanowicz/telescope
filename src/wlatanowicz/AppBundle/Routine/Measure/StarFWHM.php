<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\Measure;

use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Routine\ImageProcessing\ImagickCircleCrop;
use wlatanowicz\AppBundle\Routine\Measure\Exception\CannotMeasureException;
use wlatanowicz\AppBundle\Routine\MeasureInterface;

class StarFWHM implements MeasureInterface
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
     * @var ImagickCircleCrop
     */
    private $imagickCircleCrop;

    /**
     * @var int
     */
    private $starRadius;

    /**
     * @var int
     */
    private $starX;

    /**
     * @var int
     */
    private $starY;

    /**
     * MeasureStarDimensions constructor.
     * @param $threshold
     */
    public function __construct(
        ImagickCircleCrop $imagickCircleCrop,
        float $threshold,
        float $half = 0.5
    ) {
        $this->imagickCircleCrop = $imagickCircleCrop;
        $this->threshold = $threshold;
        $this->half = $half;

        $this->starRadius = 40;
    }

    public function setStar(int $radius, int $x = null, int $y = null)
    {
        $this->starX = $x;
        $this->starY = $y;
        $this->starRadius = $radius;
    }

    public function measure(ImagickImage $image): float
    {
        $croppedImage = $this->imagickCircleCrop->crop(
            $image,
            $this->starRadius,
            $this->starX,
            $this->starY
        );

        $width = $croppedImage->getWidth();
        $height = $croppedImage->getHeight();

        $range = Range::ONE();

        $area = 0;

        $minValue = null;
        $maxValue = null;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $value = $croppedImage->getBrightness($x, $y)->inRange($range)->getValue();
                if ($value > $this->threshold) {
                    $maxValue = $maxValue === null || $value > $maxValue
                        ? $value
                        : $maxValue;
                }
            }
        }

        if ($maxValue === null) {
            throw new CannotMeasureException("No pixels above threshold");
        }

        $halfWidthThreshold = $this->threshold + (($maxValue - $this->threshold) * $this->half);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($croppedImage->getBrightness($x, $y)->inRange($range)->getValue() > $halfWidthThreshold) {
                    $area++;
                }
            }
        }

        if ($area <= 0) {
            throw new CannotMeasureException("No star area");
        }

        return sqrt( $area / M_PI );
    }
}
