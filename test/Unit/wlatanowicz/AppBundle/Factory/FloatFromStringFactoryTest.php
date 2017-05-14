<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Factory\FloatFromStringFactory as FloatFromStringFactory;

class FloatFromStringFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FloatFromStringFactory
     */
    private $floatFromStringFactory;

    /**
     * @before
     */
    public function prepare()
    {
        $this->floatFromStringFactory = new FloatFromStringFactory();
    }

    const TOLERANCE = 0.0000001;

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldConvertStringToFloat(string $string, float $float)
    {
        $result = $this->floatFromStringFactory->floatFromString($string);

        $this->assertGreaterThan($float - self::TOLERANCE, $result);
        $this->assertLessThan($float + self::TOLERANCE, $result);
    }

    public function dataProvider()
    {
        return [
            ["1/4000", 0.00025],
            ["1/200", 0.005],
            ["1/10", 0.1],
            ["1", 1],
            ["10", 10],
            ["2.5", 2.5],
            ["30", 30],
            ["1200", 1200],
        ];
    }
}