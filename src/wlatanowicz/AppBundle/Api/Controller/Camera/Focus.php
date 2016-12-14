<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Api\Controller\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\BinaryImageWithFocusMeasure;
use wlatanowicz\AppBundle\Data\FocusMeasure;
use wlatanowicz\AppBundle\Data\GdImage;
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
        $time = 1;
        $x = 0;
        $y = 0;
        $width = 10;
        $height = 10;

        $camera = $this->cameraProvider->getCamera($name);
        $image = $camera->exposure($time);

        $gdImage = GdImage::fromBinaryImage($image);
        $croppedImage = $gdImage->imageByCropping($x, $y, $width, $height);

        $result = new BinaryImageWithFocusMeasure(
            BinaryImage::fromGdImage($croppedImage, 'image/jpeg'),
            FocusMeasure::fromGdImage($croppedImage)
        );

        $json = $this->serializer->serialize($result, 'json');
        return new JsonResponse($json, 200, [], true);
    }
}
