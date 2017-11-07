<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Routine\ImageProcessing;

use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\ImagickCameraInterface;

class ImagickCircleCrop
{
    public function crop(ImagickImage $imagickImage, int $radius, int $x = null, int $y = null): ImagickImage
    {
        $x = $x ?? (int)round($imagickImage->getWidth() / 2);
        $y = $y ?? (int)round($imagickImage->getHeight() / 2);

        $imagickImage->crop(
            $x - $radius,
            $y - $radius,
            $radius * 2,
            $radius * 2
        );

        return $imagickImage;
    }
}
