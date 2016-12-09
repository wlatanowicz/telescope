<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\BinaryImage;

interface CameraInterface
{
    /**
     * @param int $time in seconds
     * @return mixed
     */
    public function exposure(int $time): BinaryImage;
}
