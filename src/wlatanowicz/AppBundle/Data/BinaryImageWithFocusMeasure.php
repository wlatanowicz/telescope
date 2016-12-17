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
     * @var Focus
     */
    private $focusMeasure;

    /**
     * BinaryImageWithFocusMeasure constructor.
     * @param BinaryImage $binaryImage
     * @param Focus $focusMeasure
     */
    public function __construct(BinaryImage $binaryImage, Focus $focusMeasure)
    {
        $this->binaryImage = $binaryImage;
        $this->focusMeasure = $focusMeasure;
    }
}
