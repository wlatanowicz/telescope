<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Routine\Math;

use wlatanowicz\AppBundle\Data\Point;
use wlatanowicz\AppBundle\Data\Polynomial;

class LinearRegression implements InterpolationInterface
{
    /**
     * @param Point[] $points
     * @return Polynomial
     */
    public function calculate(array $points): Polynomial
    {
        $n = count($points);

        $sumX = 0;
        $sumY = 0;
        $sumXsq = 0;
        $sumXY = 0;

        foreach($points as $point) {
            $sumX += $point->getX();
            $sumY += $point->getY();
            $sumXsq += $point->getX() ** 2;
            $sumXY += $point->getX() * $point->getY();
        }

        $a = (($n * $sumXY) - ($sumX * $sumY)) / (($n * $sumXsq) - ($sumX ** 2));

        $b = ($sumY - ($a * $sumX)) / $n;

        return new Polynomial([$a, $b]);
    }
}
