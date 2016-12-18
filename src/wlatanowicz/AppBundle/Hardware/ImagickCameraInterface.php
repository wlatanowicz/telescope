<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\ImagickImage;

interface ImagickCameraInterface
{
    /**
     * @param int $time in seconds
     * @return ImagickImage
     */
    public function exposure(int $time): ImagickImage;
}