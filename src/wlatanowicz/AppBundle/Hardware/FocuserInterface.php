<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

interface FocuserInterface
{
    public function setPosition(
        float $position,
        bool $wait = true,
        float $tolerance = 5
    );

    public function getPosition(): float;
}
