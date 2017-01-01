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
     * @var string|null
     */
    private $default;

    /**
     * CameraProvider constructor.
     * @param \wlatanowicz\AppBundle\Hardware\CameraInterface[] $camera
     * @param string|null $default
     */
    public function __construct(array $camera, string $default = null)
    {
        $this->cameras = $camera;
        $this->default = $default;
    }

    /**
     * @param string|null $name
     * @return CameraInterface
     */
    public function getCamera(string $name = null): CameraInterface
    {
        return $this->cameras[$name ?? $this->default];
    }
}
