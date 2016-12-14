<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Dummy;

use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class Focuser implements FocuserInterface
{
    /**
     * @var int
     */
    private $position;

    public function setPosition(
        float $position,
        bool $wait = true,
        float $tolerance = 5
    ) {
        $this->position = round($position);
    }

    public function getPosition(): float
    {
        return $this->position;
    }
}
