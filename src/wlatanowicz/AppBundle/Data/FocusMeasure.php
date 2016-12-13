<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class FocusMeasure
{
    /**
     * @var StarDimensions
     */
    private $starDimensions;

    /**
     * FocusMeasure constructor.
     * @param StarDimensions $starDimensions
     */
    public function __construct(StarDimensions $starDimensions)
    {
        $this->starDimensions = $starDimensions;
    }


    public static function fromBinaryImage(BinaryImage $image): self
    {
        return self::fromGdImage(GdImage::fromBinaryImage($image));
    }

    public static function fromGdImage(GdImage $image): self
    {
        return new self(
            StarDimensions::fromGdImage($image, 0.2)
        );
    }

}
