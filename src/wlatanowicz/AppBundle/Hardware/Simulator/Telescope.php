<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\TelescopeInterface;

class Telescope implements TelescopeInterface
{
    public function setPosition(
        Coordinates $coordinates,
        bool $wait = true,
        Coordinates $tolerance = null
    ) {
        // TODO: Implement setPosition() method.
    }

    public function getPosition(): Coordinates
    {
        return new Coordinates(0.5, 0.5);
    }
}
