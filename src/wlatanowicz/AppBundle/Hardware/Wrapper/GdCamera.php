<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Wrapper;

use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\GdCameraInterface;

class GdCamera implements GdCameraInterface
{
    /**
     * @var CameraInterface
     */
    private $camera;

    /**
     * GdCamera constructor.
     * @param CameraInterface $camera
     */
    public function __construct(CameraInterface $camera)
    {
        $this->camera = $camera;
    }


    public function exposure(int $time): GdImage
    {
        $image = $this->camera->exposure($time);
        return GdImage::fromBinaryImage($image);
    }
}
