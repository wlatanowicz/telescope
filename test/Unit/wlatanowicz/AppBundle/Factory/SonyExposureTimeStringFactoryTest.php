<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Factory\FloatFromStringFactory;
use wlatanowicz\AppBundle\Factory\SonyExposureTimeStringFactory;

class SonyExposureTimeStringFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FloatFromStringFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $floatFromStringMock;

    /**
     * @var SonyExposureTimeStringFactory
     */
    private $sonyExposureTimeStringFactory;

    /**
     * @before
     */
    public function prepare()
    {
        $this->floatFromStringMock = new FloatFromStringFactory();

        $this->sonyExposureTimeStringFactory = new SonyExposureTimeStringFactory(
            $this->floatFromStringMock
        );
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldConvertFloatToString(float $float, string $expected)
    {
        $result = $this->sonyExposureTimeStringFactory->exposureStringFromFloat($float);
        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return [
            [0.00025, "1/4000"],
            [0.0005, "1/2000"],
            [0.1, "1/10"],
            [2.5, "2.5"],
            [10, "10"],
            [30, "30"],
            [300, SonyExposureTimeStringFactory::BULB],
            [1200, SonyExposureTimeStringFactory::BULB],
        ];
    }
}