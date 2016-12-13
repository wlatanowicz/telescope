<?php
declare(strict_types=1);

namespace Unit\wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Factory\TelescopeCoordinatesConverter;

class CoordinatesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $string
     * @param Coordinates $coordinates
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldConvertStringToCoordinates(string $string, Coordinates $expectedCoordinates)
    {
        $result = Coordinates::fromString($string);
        $this->assertEquals($expectedCoordinates, $result);
    }

    /**
     * @param string $string
     * @param Coordinates $coordinates
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldConvertCoordinatesToString(string $expectedString, Coordinates $coordinates)
    {
        $result = $coordinates->toString();
        $this->assertEquals($expectedString, $result);
    }



    public function dataProvider()
    {
        return[
            [
                "string" => "00000000,00000000",
                "coordinates" => new Coordinates(
                    0,
                    0
                ),
            ],
        ];
    }
}
