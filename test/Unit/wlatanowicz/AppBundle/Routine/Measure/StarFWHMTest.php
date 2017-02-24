<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Routine\Measure;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;
use wlatanowicz\AppBundle\Routine\Measure\Exception\CannotMeasureException;
use wlatanowicz\AppBundle\Routine\Measure\StarFWHM as StarFWHM;

class StarFWHMTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function itShouldMeasureStarSize(
        string $filename,
        float $threshold,
        float $half,
        int $pixelCount
    ) {
        $starFWHM = new StarFWHM(
            $threshold,
            $half
        );

        $binData = file_get_contents(__DIR__ . "/../Resources/" . $filename);
        $binImage = new BinaryImage($binData);
        $factory = new ImagickImageFactory();
        $imagickImage = $factory->fromBinaryImage($binImage);

        $result = $starFWHM->measure($imagickImage);
        $expected = sqrt($pixelCount / M_PI);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return  [
            [
                "fake_star_2.png",
                0.1,
                0.5,
                4,
            ],
            [
                "fake_star_4.png",
                0.1,
                0.15,
                12,
            ],
            [
                "fake_star_4.png",
                0.1,
                0.2,
                4,
            ],
            [
                "fake_star_4.png",
                0.1,
                0.6,
                4,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForExceptions
     */
    public function itShouldThrowExcrption(
        string $filename,
        float $threshold,
        float $half,
        string $exception
    ) {
        $starFWHM = new StarFWHM(
            $threshold,
            $half
        );

        $binData = file_get_contents(__DIR__ . "/../Resources/" . $filename);
        $binImage = new BinaryImage($binData);
        $factory = new ImagickImageFactory();
        $imagickImage = $factory->fromBinaryImage($binImage);

        $this->expectException($exception);
        $starFWHM->measure($imagickImage);
    }

    public function dataProviderForExceptions()
    {
        return  [
            [
                "fake_star_2.png",
                1.1,
                0.5,
                CannotMeasureException::class,
            ],
        ];
    }
}