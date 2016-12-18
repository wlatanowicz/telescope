<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Routine;


use wlatanowicz\AppBundle\Data\ImagickImage;

interface MeasureInterface
{
    public function measure(ImagickImage $image): float;
}