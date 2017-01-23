<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Hardware\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use wlatanowicz\AppBundle\Controller\Hardware\Camera\Image;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CameraProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cameraProviderMock;

    /**
     * @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * @var CameraInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cameraMock;

    /**
     * @var Image
     */
    private $controller;

    /**
     * @before
     */
    public function prepare()
    {
        $this->cameraProviderMock = $this->createMock(CameraProvider::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);
        $this->cameraMock = $this->createMock(CameraInterface::class);
        $this->controller = new Image(
            $this->cameraProviderMock,
            $this->serializerMock
        );
    }

    /**
     * @test
     */
    public function itShouldExpose()
    {
        $name = "some-camera";
        $time = 187;

        $image = new BinaryImage("binary stream", "image/jpeg");
        $json = '{"some":"json"}';

        $this->cameraProviderMock
            ->expects($this->once())
            ->method('getCamera')
            ->with($name)
            ->willReturn($this->cameraMock);

        $this->cameraMock
            ->expects($this->once())
            ->method('exposure')
            ->with($time)
            ->willReturn($image);

        $this->serializerMock
            ->expects($this->once())
            ->method('serialize')
            ->with(
                $image,
                'json'
            )
            ->willReturn($json);

        $request = new Request(
            [
                "time" => $time,
            ]
        );

        $expected = new JsonResponse(
            $json,
            200,
            [],
            true
        );

        $result = $this->controller->getImage(
            $name,
            $request
        );

        $this->assertEquals($expected, $result);
    }
}
