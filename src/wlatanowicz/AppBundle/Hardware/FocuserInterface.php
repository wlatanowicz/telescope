<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

interface FocuserInterface
{
    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = null
    );

    public function getPosition(): int;
}
