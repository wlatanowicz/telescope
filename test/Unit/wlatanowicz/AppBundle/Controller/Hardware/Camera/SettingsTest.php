<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Hardware\Camera;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use wlatanowicz\AppBundle\Controller\Hardware\Camera\Settings;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class SettingsTest extends \PHPUnit_Framework_TestCase
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
     * @var Settings
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
        $this->controller = new Settings(
            $this->cameraProviderMock,
            $this->serializerMock
        );
    }

    /**
     * @test
     */
    public function itShouldSetFormat()
    {
        $format = CameraInterface::FORMAT_JPEG;
        $name = "some-camera";

        $this->cameraProviderMock
            ->expects($this->once())
            ->method('getCamera')
            ->with($name)
            ->willReturn($this->cameraMock);

        $this->cameraMock
            ->expects($this->once())
            ->method('setFormat')
            ->with($format);

        $request = new Request([], [], [], [], [], [], \json_encode($format));

        $expected = new JsonResponse(\json_encode($format), 200, [], true);

        $result = $this->controller->setFormat($name, $request);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function itShouldGetFormat()
    {
        $format = CameraInterface::FORMAT_JPEG;
        $name = "some-camera";

        $this->cameraProviderMock
            ->expects($this->once())
            ->method('getCamera')
            ->with($name)
            ->willReturn($this->cameraMock);

        $this->cameraMock
            ->expects($this->once())
            ->method('getFormat')
            ->willReturn($format);

        $expected = new JsonResponse(\json_encode($format), 200, [], true);

        $result = $this->controller->getFormat($name);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function itShouldSetIso()
    {
        $iso = 800;
        $name = "some-camera";

        $this->cameraProviderMock
            ->expects($this->once())
            ->method('getCamera')
            ->with($name)
            ->willReturn($this->cameraMock);

        $this->cameraMock
            ->expects($this->once())
            ->method('setIso')
            ->with($iso);

        $request = new Request([], [], [], [], [], [], \json_encode($iso));

        $expected = new JsonResponse(\json_encode($iso), 200, [], true);

        $result = $this->controller->setIso($name, $request);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function itShouldGetIso()
    {
        $iso = 1600;
        $name = "some-camera";

        $this->cameraProviderMock
            ->expects($this->once())
            ->method('getCamera')
            ->with($name)
            ->willReturn($this->cameraMock);

        $this->cameraMock
            ->expects($this->once())
            ->method('getIso')
            ->willReturn($iso);

        $expected = new JsonResponse(\json_encode($iso), 200, [], true);

        $result = $this->controller->getIso($name);

        $this->assertEquals($expected, $result);
    }
}
