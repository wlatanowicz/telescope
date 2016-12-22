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

        $this->x = null;
        $this->y = null;
        $this->radius = 40;
    }

    public function exposure(int $time): ImagickImage
    {
        echo ".";
        $imagickImage = $this->camera->exposure($time);

        $x = $this->x ?? (int)round($imagickImage->getWidth() / 2);
        $y = $this->y ?? (int)round($imagickImage->getHeight() / 2);

        $imagickImage->crop(
            $x - ($this->radius),
            $y - ($this->radius),
            $this->radius,
            $this->radius
        );

        return $imagickImage;
    }

    public function setCroping(int $radius, int $x = null, int $y = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->radius = $radius;
    }
}
