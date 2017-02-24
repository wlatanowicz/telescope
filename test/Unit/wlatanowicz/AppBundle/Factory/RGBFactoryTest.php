<?php

namespace Test\Unit\wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\HSV;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue;
use wlatanowicz\AppBundle\Data\RGB;
use wlatanowicz\AppBundle\Factory\HSVFactory;
use wlatanowicz\AppBundle\Factory\RGBFactory;

class RGBFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RGBFactory
     */
    private $factory;

    /**
     * @before
     */
    public function prepare()
    {
        $hsvFactory = new HSVFactory();
        $this->factory = new RGBFactory($hsvFactory);
    }

    /**
     * @test
     * @dataProvider hsvDataProvider
     */
    public function itShouldConvertHSVtoRGB(RGB $rgb, HSV $hsv)
    {
        $expected = $rgb;

        $result = $this->factory->fromHSV($hsv);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @dataProvider collectionDataProvider
     * @param array $collection
     * @param RGB $rgb
     */
    public function itShouldSummarizeColors(array $collection, RGB $rgb)
    {
        $expected = $rgb;
        $result = $this->factory->fromCollection($collection);

        $this->assertEquals($expected, $result);
    }

    public function hsvDataProvider()
    {
        $degreeRange = Range::DEGREE();
        $unitRange = Range::ONE();

        return [
            "red" => [
                "rgb" => new RGB(
                    new RangedValue(1, $unitRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(0, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(0, $degreeRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
            ],
            "green" => [
                "rgb" => new RGB(
                    new RangedValue(0, $unitRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(0, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(120, $degreeRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
            ],
            "blue" => [
                "rgb" => new RGB(
                    new RangedValue(0, $unitRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(240, $degreeRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
            ],
            "white-1" => [
                "rgb" => new RGB(
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(0, $degreeRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
            ],
            "white-2" => [
                "rgb" => new RGB(
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(120, $degreeRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
            ],
            "white-3" => [
                "rgb" => new RGB(
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(240, $degreeRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(1, $unitRange)
                ),
            ],
            "black" => [
                "rgb" => new RGB(
                    new RangedValue(0, $unitRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(0, $unitRange)
                ),
                "hsv" => new HSV(
                    new RangedValue(0, $degreeRange),
                    new RangedValue(0, $unitRange),
                    new RangedValue(0, $unitRange)
                ),
            ],
        ];
    }

    public function collectionDataProvider()
    {
        return [
            [
                [
                    new RGB(RangedValue::ONE(), RangedValue::ZERO(), RangedValue::ZERO()),
                    new RGB(RangedValue::ZERO(), RangedValue::ONE(), RangedValue::ZERO()),
                    new RGB(RangedValue::ZERO(), RangedValue::ZERO(), RangedValue::ONE()),
                ],
                new RGB(RangedValue::ONE(), RangedValue::ONE(), RangedValue::ONE())
            ],
            [
                [
                    new RGB(RangedValue::ONE(), RangedValue::ZERO(), RangedValue::ZERO()),
                    new RGB(RangedValue::ONE(), RangedValue::ZERO(), RangedValue::ZERO()),
                    new RGB(RangedValue::ONE(), RangedValue::ZERO(), RangedValue::ZERO()),
                ],
                new RGB(RangedValue::ONE(), RangedValue::ZERO(), RangedValue::ZERO())
            ]
        ];
    }
}
