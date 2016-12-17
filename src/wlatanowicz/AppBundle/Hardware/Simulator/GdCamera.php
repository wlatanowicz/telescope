<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use wlatanowicz\AppBundle\Data\GdImage;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\GdCameraInterface;

class GdCamera implements GdCameraInterface
{
    /**
     * @var FocuserInterface
     */
    private $focuser;

    /**
     * GdCamera constructor.
     * @param FocuserInterface $focuser
     */
    public function __construct(FocuserInterface $focuser)
    {
        $this->focuser = $focuser;
    }

    public function exposure(int $time): GdImage
    {

    }

}
