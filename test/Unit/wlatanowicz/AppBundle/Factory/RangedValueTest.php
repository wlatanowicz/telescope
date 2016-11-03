<?php
declare(strict_types=1);

namespace Unit\wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue;
use wlatanowicz\AppBundle\Factory\RangedValue as RangedValueFactory;

class RangedValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     * @param RangedValue $input
     * @param RangedValue $expected
     */
    public function itShouldConvertRangedValue(RangedValue $input, RangedValue $expected)
    {
        $targetRange = $expected->getRange();
        $factory = new RangedValueFactory($targetRange);
        $result = $factory->convert($input);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return [
            [
                "input" => new RangedValue(50, new Range(0, 100)),
                "expected" => new RangedValue(5, new Range(0, 10)),
            ],
            [
                "input" => new RangedValue(160, new Range(0, 200)),
                "expected" => new RangedValue(8, new Range(0, 10)),
            ],
            [
                "input" => new RangedValue(0, new Range(-100, 100)),
                "expected" => new RangedValue(0, new Range(-10, 10)),
            ],
            [
                "input" => new RangedValue(50, new Range(-100, 100)),
                "expected" => new RangedValue(5, new Range(-10, 10)),
            ],
            [
                "input" => new RangedValue(-50, new Range(-100, 100)),
                "expected" => new RangedValue(-5, new Range(-10, 10)),
            ],
            [
                "input" => new RangedValue(4, new Range(0, 10)),
                "expected" => new RangedValue(40, new Range(0, 100)),
            ],
        ];
    }
}
