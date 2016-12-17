<?php
declare(strict_types=1);

namespace Unit\wlatanowicz\AppBundle\Routine;


use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Data\StarDimensions;
use wlatanowicz\AppBundle\Routine\MeasureStarDimensions;

class MeasureStarDimensionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldMeasureFocus(float $threshold, string $file, StarDimensions $expected)
    {
        $data = file_get_contents($file);
        $image = new BinaryImage($data);
        $gdimage = GdImage::fromBinaryImage($image);
        $measure = new MeasureStarDimensions($threshold);
        $result = $measure->measure($gdimage);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return [
            [
                "threshold" => 0.2,
                "file" => __DIR__."/Resources/fake_star_2.png",
                "expected" => new StarDimensions(2, 2),
            ],
            [
                "threshold" => 0.2,
                "file" => __DIR__."/Resources/fake_star_4.png",
                "expected" => new StarDimensions(4, 4),
            ],
        ];
    }
}
