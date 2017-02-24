<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\RGBMatrix;
use wlatanowicz\AppBundle\Data\SpectrumMatrix;

class RGBMatrixFactory
{
    /**
     * @var RGBFactory
     */
    private $rgbFactory;

    /**
     * RGBMatrixFactory constructor.
     * @param RGBFactory $rgbFactory
     */
    public function __construct(RGBFactory $rgbFactory)
    {
        $this->rgbFactory = $rgbFactory;
    }

    public function fromSpectrumMatrix(SpectrumMatrix $matrix): RGBMatrix
    {
        $pixels = new RGBMatrix();
        for ($x=0; $x < $matrix->getWidth(); $x++) {
            for ($y=0; $y < $matrix->getHeight(); $y++) {
                $pixels->setPoint(
                    $x,
                    $y,
                    $this->rgbFactory->fromSpectrum(
                        $matrix->getSpectrum(
                            $x,
                            $y
                        )
                    )
                );
            }
        }
        return $pixels;
    }
}
