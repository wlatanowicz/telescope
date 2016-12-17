<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Wrapper;

use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\GdCameraInterface;

class GdCircleCroppingCamera implements GdCameraInterface
{
    /**
     * @var GdCameraInterface
     */
    private $camera;

    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var int
     */
    private $radius;

    /**
     * GdCamera constructor.
     * @param GdCameraInterface $camera
     */
    public function __construct(GdCameraInterface $camera)
    {
        $this->camera = $camera;
    }


    public function exposure(int $time): GdImage
    {
        $gdImage = $this->camera->exposure($time);
        return $gdImage->imageByCropping(
            $this->x - ($this->radius),
            $this->y - ($this->radius),
            $this->radius,
            $this->radius
        );
    }
}
