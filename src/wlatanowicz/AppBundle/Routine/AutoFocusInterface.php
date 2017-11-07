<?php

namespace wlatanowicz\AppBundle\Routine;

use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\CameraInterface;

interface AutoFocusInterface
{
    public function autofocus(
        MeasureInterface $measure,
        CameraInterface $camera,
        FocuserInterface $focuser,
        int $minPosition,
        int $maxPosition,
        int $time
    ): AutofocusResult;
}