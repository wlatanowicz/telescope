<?php
declare(strict_types=1);

namespace Unit\wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Routine\AutoFocus;
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

    private $currentFocuserPosition;

    /**
     * @before
     */
    public function prepare()
    {
        $this->focuser = $this->focuserMock();
        $this->camera = $this->cameraMock();
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

        $autofocus = new AutoFocus(
            $measure,
            $this->camera,
            $this->focuser,
            $minPosition,
            $maxPosition,
            $partials,
            $iterations
        );

        $result = $autofocus->autofocus(1, 0, 0, 10, 10);

        $minExpected = $focusPoint - abs($tolerance);
        $maxExpected = $focusPoint + abs($tolerance);

        $this->assertGreaterThanOrEqual($minExpected, $result);
        $this->assertLessThanOrEqual($maxExpected, $result);
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
                "iterations" => 5
            ],
        ];
    }

    private function cameraMock(): CameraInterface
    {
        $imageRes = imagecreatetruecolor(100, 100);
        $gdImage = new GdImage($imageRes);
        $image = BinaryImage::fromGdImage($gdImage, "image/jpeg");

        $camera = $this->createMock(CameraInterface::class);
        $camera
            ->expects($this->any())
            ->method('exposure')
            ->willReturn($image);

        return $camera;
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
                function (GdImage $image) use ($focusPosition, $slope1, $slope2) {
                    $val = $this->currentFocuserPosition <= $focusPosition
                        ? $slope1 * $this->currentFocuserPosition - $slope1 * $focusPosition
                        : (-$slope2) * $this->currentFocuserPosition + $slope2 * $focusPosition;
                    return -$val;
                }
            );
        return $measure;
    }
}
