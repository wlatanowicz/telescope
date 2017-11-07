<?php
declare(strict_types=1);

namespace Unit\wlatanowicz\AppBundle\Routine;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\BinaryImages;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Routine\AutoFocus\SimpleRecursive;
use wlatanowicz\AppBundle\Routine\MeasureInterface;

class AutoFocusTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FocuserInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $focuser;

    /**
     * @var CameraInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $camera;

    /**
     * @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var ImagickImageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $imagickImageFactory;

    private $currentFocuserPosition;

    /**
     * @before
     */
    public function prepare()
    {
        $this->focuser = $this->focuserMock();
        $this->camera = $this->cameraMock();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->imagickImageFactory = $this->imagickImageFactoryMock();
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldFindBestFocus(
        int $tolerance,
        int $minPosition,
        int $maxPosition,
        int $focusPoint,
        float $focusingSlope1,
        float $focusingSlope2,
        int $partials,
        int $iterations
    ) {
        $measure = $this->measureMock(
            $focusPoint,
            $focusingSlope1,
            $focusingSlope2
        );

        $autofocus = new SimpleRecursive(
            $this->logger,
            $this->imagickImageFactory
        );

        $autofocus->setPartials($partials);
        $autofocus->setIterations($iterations);

        $result = $autofocus->autofocus(
            $measure,
            $this->camera,
            $this->focuser,
            $minPosition,
            $maxPosition,
            1
        );

        $minExpected = $focusPoint - abs($tolerance);
        $maxExpected = $focusPoint + abs($tolerance);

        $this->assertGreaterThanOrEqual($minExpected, $result->getMaximum()->getPosition());
        $this->assertLessThanOrEqual($maxExpected, $result->getMaximum()->getPosition());
    }

    public function dataProvider(): array
    {
        return [
            [
                "tolerance" => 10,
                "minPosition" => 1000,
                "maxPosition" => 4000,
                "focusPoint" => 3231,
                "focusingSlope1" => 1.5,
                "focusingSlope2" => 1.5,
                "partials" => 5,
                "iterations" => 6
            ],
            [
                "tolerance" => 5,
                "minPosition" => 1000,
                "maxPosition" => 5000,
                "focusPoint" => 1345,
                "focusingSlope1" => 2,
                "focusingSlope2" => 0.5,
                "partials" => 7,
                "iterations" => 7
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForConstMeasure
     */
    public function itShouldFindBestFocusOnCenterWhenMeasureIsConstant(
        int $tolerance,
        int $minPosition,
        int $maxPosition,
        int $partials,
        int $iterations
    ) {
        $measure = $this->createMock(MeasureInterface::class);
        $measure->expects($this->any())
            ->method('measure')
            ->willReturn(1);

        $autofocus = new SimpleRecursive(
            $this->logger,
            $this->imagickImageFactory
        );

        $autofocus->setPartials($partials);
        $autofocus->setIterations($iterations);

        $result = $autofocus->autofocus(
            $measure,
            $this->camera,
            $this->focuser,
            $minPosition,
            $maxPosition,
            1
        );

        $focusPoint = ($maxPosition + $minPosition) / 2;

        $minExpected = $focusPoint - abs($tolerance);
        $maxExpected = $focusPoint + abs($tolerance);

        $this->assertGreaterThanOrEqual($minExpected, $result->getMaximum()->getPosition());
        $this->assertLessThanOrEqual($maxExpected, $result->getMaximum()->getPosition());
    }

    public function dataProviderForConstMeasure(): array
    {
        return [
            [
                "tolerance" => 1,
                "minPosition" => 1000,
                "maxPosition" => 4000,
                "partials" => 5,
                "iterations" => 6
            ],
            [
                "tolerance" => 1,
                "minPosition" => 1000,
                "maxPosition" => 5000,
                "partials" => 7,
                "iterations" => 7
            ],
        ];
    }

    private function cameraMock(): CameraInterface
    {
        $binaryImages = new BinaryImages([]);
        $camera = $this->createMock(CameraInterface::class);
        $camera
            ->expects($this->any())
            ->method('exposure')
            ->willReturn($binaryImages);

        return $camera;
    }

    private function imagickImageFactoryMock(): ImagickImageFactory
    {
        $imagickImage = new ImagickImage(new \Imagick());
        $mock = $this->createMock(ImagickImageFactory::class);
        $mock
            ->expects($this->any())
            ->method('fromBinaryImages')
            ->willReturn($imagickImage);

        return $mock;
    }

    private function focuserMock(): FocuserInterface
    {
        $focuser = $this->createMock(FocuserInterface::class);
        $focuser
            ->expects($this->any())
            ->method('setPosition')
            ->willReturnCallback(
                function ($position, $wait = false) {
                    $this->currentFocuserPosition = $position;
                }
            );
        $focuser
            ->expects($this->any())
            ->method('getPosition')
            ->willReturnCallback(
                function () {
                    return $this->currentFocuserPosition;
                }
            );
        return $focuser;
    }

    private function measureMock(int $focusPosition, float $slope1, float $slope2): MeasureInterface
    {
        $measure = $this->createMock(MeasureInterface::class);
        $measure
            ->expects($this->any())
            ->method('measure')
            ->willReturnCallback(
                function (ImagickImage $image) use ($focusPosition, $slope1, $slope2) {
                    $val = $this->currentFocuserPosition <= $focusPosition
                        ? $slope1 * $this->currentFocuserPosition - $slope1 * $focusPosition
                        : (-$slope2) * $this->currentFocuserPosition + $slope2 * $focusPosition;
                    return -$val;
                }
            );
        return $measure;
    }
}
