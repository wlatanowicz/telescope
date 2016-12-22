<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\Wrapper\ImagickCircleCroppingCamera;

class ImagickCroppedCameraProvider
{
    /**
     * @var ImagickCircleCroppingCamera[]
     */
    private $cameras;

    /**
     * ImagickCameraProvider constructor.
     * @param ImagickCircleCroppingCamera[] $cameras
     */
    public function __construct(array $cameras)
    {
        $this->cameras = $cameras;
    }

    public function getCamera(string $name): ImagickCircleCroppingCamera
    {
        return $this->cameras[$name];
    }
}
