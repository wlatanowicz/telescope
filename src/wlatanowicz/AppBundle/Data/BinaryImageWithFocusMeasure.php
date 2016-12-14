<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class BinaryImageWithFocusMeasure
{
    /**
     * @var BinaryImage
     */
    private $binaryImage;

    /**
     * @var FocusMeasure
     */
    private $focusMeasure;

    /**
     * BinaryImageWithFocusMeasure constructor.
     * @param BinaryImage $binaryImage
     * @param FocusMeasure $focusMeasure
     */
    public function __construct(BinaryImage $binaryImage, FocusMeasure $focusMeasure)
    {
        $this->binaryImage = $binaryImage;
        $this->focusMeasure = $focusMeasure;
    }
}
