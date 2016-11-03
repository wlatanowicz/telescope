<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Data;

use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\Spectrum;
use wlatanowicz\AppBundle\Data\SpectrumPoint;

class SpectrumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateFrequencyRange()
    {
        $dataPoints = [
            new SpectrumPoint(10, 100),
            new SpectrumPoint(20, 200),
            new SpectrumPoint(30, 300),
        ];

        $spectrum = new Spectrum($dataPoints);

        $result = $spectrum->getFrequencyRange();
        $expected = new Range(10, 30);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function itShouldCalculatePowerRange()
    {
        $dataPoints = [
            new SpectrumPoint(10, 100),
            new SpectrumPoint(20, 200),
            new SpectrumPoint(30, 300),
        ];

        $spectrum = new Spectrum($dataPoints);

        $result = $spectrum->getPowerRange();
        $expected = new Range(100, 300);

        $this->assertEquals($expected, $result);
    }
}
