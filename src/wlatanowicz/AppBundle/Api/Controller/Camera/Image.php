<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Camera;

use Symfony\Component\HttpFoundation\JsonResponse;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class Image
{
    /**
     * @var CameraProvider
     */
    private $cameraProvider;

    /**
     * Image constructor.
     * @param CameraProvider $cameraProvider
     */
    public function __construct(CameraProvider $cameraProvider)
    {
        $this->cameraProvider = $cameraProvider;
    }

    public function getImage(string $name)
    {
        $time = 1;
        $camera = $this->cameraProvider->getCameras($name);
        $image = $camera->exposure($time);
        return new JsonResponse([]);
    }
}
