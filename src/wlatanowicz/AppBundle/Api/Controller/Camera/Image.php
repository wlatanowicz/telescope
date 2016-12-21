<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function getImage(string $name, Request $request): Response
    {
        $time = intval($request->query->get('time'), 10);
        $iso = intval($request->query->get('iso'), 10);
        $format = $request->query->get('format');

        $camera = $this->cameraProvider->getCamera($name);
        $image = $camera->exposure($time, $iso, $format);

        $json = $this->serializer->serialize($image, 'json');
        return new JsonResponse($json, 200, [], true);
    }
}
