<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Wrapper;

use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\ImagickCameraInterface;

class ImagickCircleCroppingCamera implements ImagickCameraInterface
{
    /**
     * @var ImagickCameraInterface
     */
    private $camera;

    /**
     * @var int|null
     */
    private $x;

    /**
     * @var int|null
     */
    private $y;

    /**
     * @var int
     */
    private $radius;

    /**
     * GdCamera constructor.
     * @param ImagickCameraInterface $camera
     */
    public function __construct(ImagickCameraInterface $camera)
    {
        $this->camera = $camera;

        $this->x = 281;//null;
        $this->y = 261;//null;
        $this->radius = 40;
    }


    public function exposure(int $time): ImagickImage
    {
        $imagickImage = $this->camera->exposure($time);

        $x = $this->x ?? (int)round($imagickImage->getWidth() / 2);
        $y = $this->y ?? (int)round($imagickImage->getHeight() / 2);

        $imagickImage->crop(
            $x - ($this->radius),
            $y - ($this->radius),
            $this->radius,
            $this->radius
        );

        file_put_contents( time()."-".rand(100,999).".jpg", $imagickImage->getImageBlob());
        return $imagickImage;
    }
}
