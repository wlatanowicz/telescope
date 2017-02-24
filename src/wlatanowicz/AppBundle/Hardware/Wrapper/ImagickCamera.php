<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Wrapper;

use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\ImagickCameraInterface;

class ImagickCamera implements ImagickCameraInterface
{
    /**
     * @var CameraInterface
     */
    private $camera;

    /**
     * @var ImagickImageFactory
     */
    private $imagickImageFactory;

    /**
     * GdCamera constructor.
     * @param CameraInterface $camera
     */
    public function __construct(CameraInterface $camera, ImagickImageFactory $imagickImageFactory)
    {
        $this->camera = $camera;
        $this->imagickImageFactory = $imagickImageFactory;
    }


    public function exposure(int $time): ImagickImage
    {
        $image = $this->camera->exposure($time);
        return $this->imagickImageFactory->fromBinaryImage($image);
    }
}
