<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\CameraInterface;

class CameraProvider
{
    /**
     * @var CameraInterface[]
     */
    private $cameras;

    /**
     * CameraProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\CameraInterface[] $camera
     */
    public function __construct(array $camera)
    {
        $this->cameras = $camera;
    }

    public function getCameras(string $name): CameraInterface
    {
        return $this->cameras[$name];
    }
}
