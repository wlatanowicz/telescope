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
     * @var string|null
     */
    private $default;

    /**
     * ImagickCameraProvider constructor.
     * @param ImagickCircleCroppingCamera[] $cameras
     * @param string|null $default
     */
    public function __construct(array $cameras, string $default = null)
    {
        $this->cameras = $cameras;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return ImagickCircleCroppingCamera
     */
    public function getCamera(string $name = null): ImagickCircleCroppingCamera
    {
        return $this->cameras[$name ?? $this->default];
    }
}
