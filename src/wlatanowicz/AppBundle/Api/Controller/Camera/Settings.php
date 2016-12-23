<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class Settings
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

    public function setFormat(string $camera, Request $request)
    {
        $format = $request->getContent();
        $this->cameraProvider->getCamera($camera)->setFormat($format);
        $json = json_encode($format);
        return new JsonResponse($json, 200, [], true);
    }

    public function setIso(string $camera, Request $request)
    {
        $iso = intval($request->getContent(), 10);
        $this->cameraProvider->getCamera($camera)->setIso($iso);
        $json = json_encode($iso);
        return new JsonResponse($json, 200, [], true);
    }
}