<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class Focuser implements FocuserInterface
{
    /**
     * @var int
     */
    private $position;

    /**
     * Focuser constructor.
     */
    public function __construct()
    {
        $this->position = 3000;
    }


    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = 5
    ) {
        $this->position = $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
