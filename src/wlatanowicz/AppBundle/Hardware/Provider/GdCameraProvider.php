<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Provider;

use wlatanowicz\AppBundle\Hardware\GdCameraInterface;

class GdCameraProvider
{
    /**
     * @var GdCameraInterface[]
     */
    private $cameras;

    /**
     * GdCameraProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\GdCameraInterface[] $camera
     */
    public function __construct(array $camera)
    {
        $this->cameras = $camera;
    }

    public function getCamera(string $name): GdCameraInterface
    {
        return $this->cameras[$name];
    }
}
