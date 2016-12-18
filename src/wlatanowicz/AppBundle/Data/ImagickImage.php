<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class ImagickImage
{
    /**
     * @var \Imagick
     */
    private $imagick;

    /**
     * ImagickImage constructor.
     * @param $imagick
     */
    public function __construct(\Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public static function fromBinaryImage(BinaryImage $binaryImage): self
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob(
            $binaryImage->getData()
        );
        return new self($imagick);
    }


    public function getWidth(): int
    {
        return $this->imagick->getImageWidth();
    }

    public function getHeight(): int
    {
        return $this->imagick->getImageHeight();
    }

    public function getColor(int $x, int $y): RGB
    {
        $color = $this->imagick->getImagePixelColor($x, $y)->getColor(1);
        return new RGB(
            new RangedValue($color['r'], new Range(0, 1)),
            new RangedValue($color['g'], new Range(0, 1)),
            new RangedValue($color['b'], new Range(0, 1))
        );
    }

    public function getBrightness(int $x, int $y): RangedValue
    {
        return $this->getColor($x, $y)->getBrightness();
    }

    public function crop(int $x, int $y, int $width, int $height)
    {
        $this->imagick->cropImage(
            $width,
            $height,
            $x,
            $y
        );
    }

    public function blur(float $radius, float $sigma)
    {
        $this->imagick->blurImage(
            $radius,
            $sigma
        );
    }

    public function getImageBlob(): string
    {
        return $this->imagick->getImageBlob();
    }

    function __clone()
    {
        return new self(
            clone $this->imagick
        );
    }


}
