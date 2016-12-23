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
        LoggerInterface $logger,
        string $logPrefix
    )
    {
        $this->logger = $logger;
        $this->logPrefix = $logPrefix;

        $this->position = 3000;
    }


    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = 5
    ) {
        $this->logger->info("[$this->logPrefix] Setting position (position={$position})");
        $this->position = $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
