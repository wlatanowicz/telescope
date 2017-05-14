<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider as CameraProvider;
use wlatanowicz\AppBundle\Helper\JobManager as JobManager;
use wlatanowicz\AppBundle\Job\CameraExpose as CameraExpose;
use wlatanowicz\AppBundle\Job\Params\CameraExposeParams;

class CameraExposeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JobManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobManagerMock;

    /**
     * @var CameraProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $providerMock;

    /**
     * @var CameraInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cameraMock;

    /**
     * @var CameraExpose
     */
    private $cameraExpose;

    /**
     * @before
     */
    public function prepare()
    {
        $this->jobManagerMock = $this->createMock(JobManager::class);
        $this->providerMock = $this->createMock(CameraProvider::class);
        $this->cameraMock = $this->createMock(CameraInterface::class);

        $this->cameraExpose = new CameraExpose(
            $this->jobManagerMock,
            $this->providerMock
        );
    }

    /**
     * @test
     */
    public function itShouldDoSomething()
    {
        $cameraName = 'cam';
        $time = 5;

        $binData = "some bin data";
        $binImage = new BinaryImage($binData, "image/jpeg");

        $fileName = "filename";

        $this->providerMock
            ->expects($this->once())
            ->method('getCamera')
            ->with($cameraName)
            ->willReturn($this->cameraMock);

        $this->cameraMock
            ->expects($this->once())
            ->method('exposure')
            ->with($time)
            ->willReturn($binImage);

        $this->jobManagerMock
            ->expects($this->once())
            ->method('saveCurrentJobResult')
            ->with(
                $fileName . ".jpeg",
                $binData
            );

        $params = new CameraExposeParams(
            $cameraName,
            $time,
            $fileName
        );

        $this->cameraExpose->start($params);
    }
}