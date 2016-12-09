<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Dummy;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;

class Camera implements CameraInterface
{
    public function exposure(int $time): BinaryImage
    {
        $bin = 'some bin data';
        return new BinaryImage($bin);
    }
}
