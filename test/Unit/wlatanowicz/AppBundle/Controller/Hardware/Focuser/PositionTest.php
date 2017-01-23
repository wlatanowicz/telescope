<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Hardware\Focuser;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use wlatanowicz\AppBundle\Controller\Hardware\Focuser\Position;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;

class PositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FocuserProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $focuserProviderMock;

    /**
     * @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * @var FocuserInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $focuserMock;

    /**
     * @var Position
     */
    private $controller;

    /**
     * @before
     */
    public function prepare()
    {
        $this->focuserProviderMock = $this->createMock(FocuserProvider::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);
        $this->focuserMock = $this->createMock(FocuserInterface::class);
        $this->controller = new Position(
            $this->focuserProviderMock,
            $this->serializerMock
        );
    }

    /**
     * @test
     */
    public function itShouldSetPosition()
    {
        $position = 3000;
        $name = "some-focuser";

        $this->focuserProviderMock
            ->expects($this->once())
            ->method('getFocuser')
            ->with($name)
            ->willReturn($this->focuserMock);

        $this->focuserMock
            ->expects($this->once())
            ->method('setPosition')
            ->with($position);

        $request = new Request([], [], [], [], [], [], \json_encode($position));

        $expected = new JsonResponse(\json_encode($position), 200, [], true);

        $result = $this->controller->setPosition($name, $request);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function itShouldGetFormat()
    {
        $position = 3000;
        $name = "some-focuser";

        $this->focuserProviderMock
            ->expects($this->once())
            ->method('getFocuser')
            ->with($name)
            ->willReturn($this->focuserMock);

        $this->focuserMock
            ->expects($this->once())
            ->method('getPosition')
            ->willReturn($position);

        $expected = new JsonResponse(\json_encode($position), 200, [], true);

        $result = $this->controller->getPosition($name);

        $this->assertEquals($expected, $result);
    }
}
