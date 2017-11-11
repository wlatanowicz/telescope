<?php

namespace wlatanowicz\AppBundle\Routine\AutoFocus;

use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Routine\Measure\MeasureInterface;

interface AutoFocusInterface
{
    public function autofocus(
        MeasureInterface $measure,
        CameraInterface $camera,
        FocuserInterface $focuser,
        int $minPosition,
        int $maxPosition,
        int $time,
        array $options = []
    ): AutofocusResult;
}