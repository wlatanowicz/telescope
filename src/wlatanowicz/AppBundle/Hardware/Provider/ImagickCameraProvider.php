<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\ImagickCameraInterface;

class ImagickCameraProvider
{
    /**
     * @var ImagickCameraInterface[]
     */
    private $cameras;

    /**
     * ImagickCameraProvider constructor.
     * @param ImagickCameraInterface[] $cameras
     */
    public function __construct(array $cameras)
    {
        $this->cameras = $cameras;
    }

    public function getCamera(string $name): ImagickCameraInterface
    {
        return $this->cameras[$name];
    }
}
