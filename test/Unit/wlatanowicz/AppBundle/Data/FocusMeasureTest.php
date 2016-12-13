<?php
declare(strict_types=1);

namespace Unit\wlatanowicz\AppBundle\Data;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\FocusMeasure;
use wlatanowicz\AppBundle\Data\StarDimensions;

class FocusMeasureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldMeasureFocus(string $file, FocusMeasure $expected)
    {
        $data = file_get_contents($file);
        $image = new BinaryImage($data);
        $result = FocusMeasure::fromBinaryImage($image);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return [
            [
                "file" => __DIR__."/Resources/fake_star_2.png",
                "expected" => new FocusMeasure(
                    new StarDimensions(2, 2)
                ),
            ],
            [
                "file" => __DIR__."/Resources/fake_star_4.png",
                "expected" => new FocusMeasure(
                    new StarDimensions(4, 4)
                ),
            ],
        ];
    }
}
