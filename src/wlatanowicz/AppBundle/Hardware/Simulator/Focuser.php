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
     * @var string
     */
    private $logPrefix;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Focuser constructor.
     */
    public function __construct(
        float $speed,
        LoggerInterface $logger,
        string $logPrefix
    )
    {
        $this->speed = $speed;

        $this->logger = $logger;
        $this->logPrefix = $logPrefix;

        $this->position = 0;
    }


    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = 5
    ) {
        $this->logger->info("[$this->logPrefix] Setting position (target={$position}, current={$this->position})");

        if ($this->speed > 0) {
            $time = (int)ceil(abs($this->position - $position) / $this->speed);
            sleep($time);
        }

        $this->position = $position;

        $this->logger->info("[$this->logPrefix] Position set (position={$position})");
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
