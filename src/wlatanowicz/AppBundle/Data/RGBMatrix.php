<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class RGBMatrix
{
    /**
     * @var RGB[][]
     */
    private $points;

    /**
     * RGBMatrix constructor.
     */
    public function __construct()
    {
        $this->points = [];
    }

    public function getWidth(): int
    {
        return 1 + max(array_keys($this->points));
    }

    public function getHeight(): int
    {
        return 1 + max(array_map(function($col) {
            return max(array_keys($col));
        }, $this->points));
    }

    public function hasPoint(int $x, int $y): bool
    {
        return isset($this->points[$x][$y]);
    }

    public function getPoint(int $x, int $y): RGB
    {
        return $this->points[$x][$y];
    }

    private function setPoint(int $x, int $y, RGB $rgb)
    {
        if (! isset($this->points[$x])) {
            $this->points[$x] = [];
        }

        $this->points[$x][$y] = $rgb;
    }

    public static function fromSpectrumMatrix(SpectrumMatrix $matrix): self
    {
        $pixels = new RGBMatrix();
        for ($x=0; $x < $matrix->getWidth(); $x++) {
            for ($y=0; $y < $matrix->getHeight(); $y++) {
                $pixels->setPoint(
                    $x,
                    $y,
                    RGB::fromSpectrum(
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
