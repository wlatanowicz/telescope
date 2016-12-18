<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class Focus
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

    public function getFocus(string $name)
    {
        //@TODO

        $json = $this->serializer->serialize([], 'json');
        return new JsonResponse($json, 200, [], true);
    }
}
