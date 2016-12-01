<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\Coordinates;

interface TelescopeInterface
{
    public function setPosition(
        Coordinates $coordinates,
        bool $wait = true,
        Coordinates $tolerance = null
    );

    public function getPosition(): Coordinates;
}
