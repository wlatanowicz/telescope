<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class Image
{
    /**
     * @var CameraProvider
     */
    private $cameraProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Image constructor.
     * @param CameraProvider $cameraProvider
     */
    public function __construct(
        CameraProvider $cameraProvider,
        SerializerInterface $serializer
    ) {
        $this->cameraProvider = $cameraProvider;
        $this->serializer = $serializer;
    }

    public function getImage(string $name)
    {
        $time = 1;
        $camera = $this->cameraProvider->getCameras($name);
        $image = $camera->exposure($time);
        $json = $this->serializer->serialize($image, 'json');
        return new JsonResponse($json, 200, [], true);
    }
}
