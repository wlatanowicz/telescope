<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\Wrapper\ImagickCircleCrop;

class ImagickCroppedCameraProvider
{
    /**
     * @var ImagickCircleCrop[]
     */
    private $cameras;

    /**
     * @var string|null
     */
    private $default;

    /**
     * ImagickCameraProvider constructor.
     * @param ImagickCircleCrop[] $cameras
     * @param string|null $default
     */
    public function __construct(array $cameras, string $default = null)
    {
        $this->cameras = $cameras;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return ImagickCircleCrop
     */
    public function getCamera(string $name = null): ImagickCircleCrop
    {
        return $this->cameras[$name ?? $this->default];
    }
}
