<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Routine\Measure;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Routine\Measure\StarFWHM as StarFWHM;

class StarFWHMTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var float
     */
    private $thresholdMock;

    /**
     * @var float
     */
    private $halfMock;

    /**
     * @var StarFWHM
     */
    private $starFWHM;

    /**
     * @before
     */
    public function prepare()
    {
        $this->thresholdMock = 0.1;
        $this->halfMock = 0.5;

        $this->starFWHM = new StarFWHM(
            $this->thresholdMock,
            $this->halfMock
        );
    }

    /**
     * @test
     */
    public function itShouldMeasureStarSize()
    {
        $binData = file_get_contents(__DIR__ . "/../Resources/fake_star_2.png");
        $binImage = new BinaryImage($binData);
        $imagickImage = ImagickImage::fromBinaryImage($binImage);

        $result = $this->starFWHM->measure($imagickImage);
        $expected = sqrt(4 / M_PI);

        $this->assertEquals($expected, $result);
    }
}