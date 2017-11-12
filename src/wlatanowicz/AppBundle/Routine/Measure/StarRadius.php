<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\Measure;

use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Data\Point;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Routine\ImageProcessing\ImagickCircleCrop;
use wlatanowicz\AppBundle\Routine\Measure\Exception\CannotMeasureException;

class StarRadius implements MeasureInterface
{
    private static $OPTION_DEFAULTS = [
        'half' => 0.5,
        'threshold' => 0.01,
        'starRadius' => 50,
        'starX' => null,
        'starY' => null,
        'biggestDistancesFactor' => 0.2,
    ];

     /**
     * @var ImagickCircleCrop
     */
    private $imagickCircleCrop;

    /**
     * @var array
     */
    private $options;

    /**
     * StarFWHM constructor.
     * @param ImagickCircleCrop $imagickCircleCrop
     */
    public function __construct(
        ImagickCircleCrop $imagickCircleCrop
    ) {
        $this->imagickCircleCrop = $imagickCircleCrop;
        $this->options = [];
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function measure(ImagickImage $image): float
    {
        $options = array_replace(self::$OPTION_DEFAULTS, $this->options);

        $croppedImage = $this->imagickCircleCrop->crop(
            $image,
            $options['starRadius'],
            $options['starX'],
            $options['starY']
        );

        $threshold = $options['threshold'];
        $half = $options['half'];

        $width = $croppedImage->getWidth();
        $height = $croppedImage->getHeight();

        $range = Range::ONE();

        $area = 0;

        $minValue = null;
        $maxValue = null;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $value = $croppedImage->getBrightness($x, $y)->inRange($range)->getValue();
                if ($value > $threshold) {
                    $maxValue = $maxValue === null || $value > $maxValue
                        ? $value
                        : $maxValue;
                }
            }
        }

        if ($maxValue === null) {
            throw new CannotMeasureException("No pixels above threshold");
        }

        $halfWidthThreshold = $threshold + (($maxValue - $threshold) * $half);

        $starPoints = [];

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($croppedImage->getBrightness($x, $y)->inRange($range)->getValue() > $halfWidthThreshold) {
                    $starPoints[] = new Point($x, $y);
                }
            }
        }

        if (count($starPoints) < 1) {
            throw new CannotMeasureException("To few star area points");
        }

        $sumX = 0;
        $sumY = 0;

        foreach ($starPoints as $starPoint) {
            /**
             * @var $starPoint Point
             */
            $sumX += $starPoint->getX();
            $sumY += $starPoint->getY();
        }

        $distances = [];

        foreach ($starPoints as $i => $starPoint1) {
            foreach ($starPoints as $j => $starPoint2) {
                if ($i > $j) {
                    $distance = 0.5 * sqrt(
                            ($starPoint1->getX() - $starPoint2->getX()) ** 2
                            + ($starPoint1->getY() - $starPoint2->getY()) ** 2
                        );

                    $distances[] = $distance;
                }
            }
        }

        rsort($distances);

        $useDistances = ceil($options['biggestDistancesFactor'] * M_PI * sqrt(count($starPoints) / M_PI));
        $distances = array_slice($distances, 0, $useDistances);

        $diameter = array_sum($distances) / count($distances);

        return $diameter;
    }
}
