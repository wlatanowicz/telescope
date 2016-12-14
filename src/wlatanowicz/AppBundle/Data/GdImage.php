<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class GdImage
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * GdImage constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public static function fromBinaryImage(BinaryImage $binaryImage): self
    {
        return new self(imagecreatefromstring($binaryImage->getData()));
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getWidth(): int
    {
        return imagesx($this->resource);
    }

    public function getHeight(): int
    {
        return imagesy($this->resource);
    }

    public function getColor(int $x, int $y): RGB
    {
        $rgb = imagecolorat($this->resource, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return new RGB(
            new RangedValue($r, new Range(0, 0xff)),
            new RangedValue($g, new Range(0, 0xff)),
            new RangedValue($b, new Range(0, 0xff))
        );
    }

    public function getBrightness(int $x, int $y): RangedValue
    {
        return $this->getColor($x, $y)->getBrightness();
    }

    public function imageByCropping(int $x, int $y, int $width, int $height): self
    {
        $cropped = imagecreatetruecolor($width, $height);
        imagecopy(
            $cropped,
            $this->resource,
            0,
            0,
            $x,
            $y,
            $width,
            $height
        );
        return new self($cropped);
    }
}
