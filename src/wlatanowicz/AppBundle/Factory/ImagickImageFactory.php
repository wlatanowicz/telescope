<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\BinaryImages;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RGBMatrix;
use wlatanowicz\AppBundle\Data\SpectrumMatrix;

class ImagickImageFactory
{
    /**
     * @var RGBMatrixFactory
     */
    private $rgbMatrixFactory;

    /**
     * ImagickImageFactory constructor.
     * @param RGBMatrixFactory $rgbMatrixFactory
     */
    public function __construct(RGBMatrixFactory $rgbMatrixFactory)
    {
        $this->rgbMatrixFactory = $rgbMatrixFactory;
    }

    public function fromBinaryImage(BinaryImage $binaryImage): ImagickImage
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob(
            $binaryImage->getData()
        );
        return new ImagickImage($imagick);
    }

    public function fromBinaryImages(BinaryImages $binaryImages): ImagickImage
    {
        $binaryImage = $binaryImages->getImageByMimetype(BinaryImage::MIMETYPE_JPEG);
        $imagick = new \Imagick();
        $imagick->readImageBlob(
            $binaryImage->getData()
        );
        return new ImagickImage($imagick);
    }

    public function fromRGBMatrix(RGBMatrix $matrix, \ImagickPixel $background = null): ImagickImage
    {
        $background = $background ?? new \ImagickPixel('rgba(0,0,0,0)');
        $width = $matrix->getWidth();
        $height = $matrix->getHeight();

        $imagick = new \Imagick();
        $imagick->newImage(
            $width,
            $height,
            $background
        );
        $imagick->setImageFormat('png');

        $draw = new \ImagickDraw();

        $range = new Range(0, 255);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if ($matrix->hasPoint($x, $y)) {
                    $point = $matrix->getPoint($x, $y);
                    $color = new \ImagickPixel(
                        sprintf(
                            "rgba(%d,%d,%d,%d)",
                            $point->getR()->inRange($range)->getValue(),
                            $point->getG()->inRange($range)->getValue(),
                            $point->getB()->inRange($range)->getValue(),
                            1
                        )
                    );
                    $draw->setFillColor($color);
                    $draw->point($x, $y);
                }
            }
        }

        $imagick->drawImage($draw);

        return new ImagickImage($imagick);
    }

    public function fromSpectrumMatrix(SpectrumMatrix $matrix): ImagickImage
    {
        $rgbMatrix = $this->rgbMatrixFactory->fromSpectrumMatrix($matrix);
        return $this->fromRGBMatrix($rgbMatrix);
    }
}
