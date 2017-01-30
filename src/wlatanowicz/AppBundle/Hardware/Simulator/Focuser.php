<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;

class Focuser implements FocuserInterface
{
    /**
     * @var int
     */
    private $position;

    /**
     * @var float
     */
    private $speed;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Focuser constructor.
     */
    public function __construct(
        float $speed,
        LoggerInterface $logger
    )
    {
        $this->speed = $speed;

        $this->logger = $logger;

        $this->position = 0;
    }


    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = null
    ) {
        $this->logger->info(
            "Setting position (current={current}, target={target})",
            [
                "current" => $this->position,
                "target" => $position
            ]
        );

        if ($this->speed > 0) {
            $time = (int)ceil(abs($this->position - $position) / $this->speed);
            sleep($time);
        }

        $this->position = $position;

        $this->logger->info(
            "Position set (position={position})",
            [
                "position" => $position,
            ]
        );
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
