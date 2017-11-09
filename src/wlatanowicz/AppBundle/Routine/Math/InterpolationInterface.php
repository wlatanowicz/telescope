<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Routine\Math;

use wlatanowicz\AppBundle\Data\Polynomial;

interface InterpolationInterface
{
    public function calculate(array $points): Polynomial;
}
