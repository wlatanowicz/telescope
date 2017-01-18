<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Controller\Hardware\Camera;

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

    public function setFormat(string $name, Request $request)
    {
        $format = json_decode($request->getContent());
        $this->cameraProvider->getCamera($name)->setFormat($format);
        $json = json_encode($format);
        return new JsonResponse($json, 200, [], true);
    }

    public function setIso(string $name, Request $request)
    {
        $iso = intval(json_decode($request->getContent()), 10);
        $this->cameraProvider->getCamera($name)->setIso($iso);
        $json = json_encode($iso);
        return new JsonResponse($json, 200, [], true);
    }

    public function getFormat(string $name)
    {
        $format = $this->cameraProvider->getCamera($name)->getFormat();
        $json = json_encode($format);
        return new JsonResponse($json, 200, [], true);
    }

    public function getIso(string $name)
    {
        $iso = $this->cameraProvider->getCamera($name)->getIso();
        $json = json_encode($iso);
        return new JsonResponse($json, 200, [], true);
    }
}
