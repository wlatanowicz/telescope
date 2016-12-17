<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Routine;


use wlatanowicz\AppBundle\Data\GdImage;

interface MeasureInterface
{
    public function measure(GdImage $image): float;
}