<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Routine\Measure;


use wlatanowicz\AppBundle\Data\ImagickImage;

interface MeasureInterface
{
    public function measure(ImagickImage $image): float;

    public function setOptions(array $options);
}