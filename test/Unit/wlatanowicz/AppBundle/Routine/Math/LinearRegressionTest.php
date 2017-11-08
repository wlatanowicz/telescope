<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Routine\Math;


use wlatanowicz\AppBundle\Data\Point;
use wlatanowicz\AppBundle\Data\Polynomial;
use wlatanowicz\AppBundle\Routine\Math\LinearRegression;

class LinearRegressionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LinearRegression
     */
    private $linearRegression;

    /**
     * @before
     */
    public function prepare()
    {
       $this->linearRegression = new LinearRegression();
    }

    /**
     * @test
     * @dataProvider dataProvider
     *
     * @param Point[] $points
     * @param Polynomial $expectedPolynomial
     */
    public function itShouldReturnProperPolynomial(array $points, Polynomial $expectedPolynomial)
    {
        $result = $this->linearRegression->calculate($points);
        $this->assertEquals($expectedPolynomial, $result, '', 0.01);
    }

    public function dataProvider()
    {
        return [
            "1" => [
                "points" => [
                    new Point(0, 0),
                    new Point(1, 1),
                ],
                "polynomial" => new Polynomial([1, 0]),
            ],
            "2" => [
                "points" => [
                    new Point(0, 1),
                    new Point(1, 0),
                ],
                "polynomial" => new Polynomial([-1, 1]),
            ],
            "3" => [
                "points" => [
                    new Point(0, 1),
                    new Point(1, 3),
                    new Point(3, 7),
                    new Point(8, 17),
                ],
                "polynomial" => new Polynomial([2, 1]),
            ],
            "4" => [
                "points" => [
                    new Point(10, 12.2),
                    new Point(21, 23.0),
                    new Point(29, 28.0),
                    new Point(37, 41.1),
                ],
                "polynomial" => new Polynomial([1.024, 1.237]),
            ],
            "5" => [
                "points" => [
                    new Point(10, 43),
                    new Point(21, 32.1),
                    new Point(28, 19.8),
                    new Point(39, 9.1),
                ],
                "polynomial" => new Polynomial([-1.201, 55.43]),
            ],
        ];
    }
}