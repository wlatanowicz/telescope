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
    private static $OPTION_DEFAULTS = [
        'half' => 0.5,
        'threshold' => 0.03,
        'starRadius' => 50,
        'starX' => null,
        'starY' => null,
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
