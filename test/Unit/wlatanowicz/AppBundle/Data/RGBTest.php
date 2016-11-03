<?php

namespace Test\Unit\wlatanowicz\AppBundle\Data;

use wlatanowicz\AppBundle\Data\HSV;
use wlatanowicz\AppBundle\Data\RGB;

class RGBTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldConvertHSVtoRGB($rgb, $hsv)
    {
        $expected = $rgb;
        $result = RGB::fromHSV($hsv);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return [
            "red" => [
                "rgb" => new RGB(1, 0, 0),
                "hsv" => new HSV(0, 1, 1),
            ],
            "green" => [
                "rgb" => new RGB(0, 1, 0),
                "hsv" => new HSV(120, 1, 1),
            ],
            "blue" => [
                "rgb" => new RGB(0, 0, 1),
                "hsv" => new HSV(240, 1, 1),
            ],

            "white-1" => [
                "rgb" => new RGB(1, 1, 1),
                "hsv" => new HSV(0, 0, 1),
            ],
            "white-2" => [
                "rgb" => new RGB(1, 1, 1),
                "hsv" => new HSV(180, 0, 1),
            ],
            "white-3" => [
                "rgb" => new RGB(1, 1, 1),
                "hsv" => new HSV(360, 0, 1),
            ],

            "black" => [
                "rgb" => new RGB(0, 0, 0),
                "hsv" => new HSV(0, 0, 0),
            ],
        ];
    }
}
