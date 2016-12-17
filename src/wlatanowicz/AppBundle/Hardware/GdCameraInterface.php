<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\GdImage;

interface GdCameraInterface
{
    /**
     * @param int $time in seconds
     * @return GdImage
     */
    public function exposure(int $time): GdImage;
}