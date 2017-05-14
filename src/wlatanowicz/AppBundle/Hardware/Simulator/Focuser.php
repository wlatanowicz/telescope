<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;

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
     * @var string|null
     */
    private $statusFile;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * Focuser constructor.
     */
    public function __construct(
        float $speed,
        string $statusFile = null,
        FileSystem $fileSystem,
        LoggerInterface $logger
    )
    {
        $this->speed = $speed;
        $this->statusFile = $statusFile;


        $this->logger = $logger;
        $this->fileSystem = $fileSystem;

        $this->position = 0;
    }


    public function setPosition(
        int $position,
        bool $wait = true,
        int $tolerance = null
    ) {
        $currentPosition = $this->getPosition();
        $this->logger->info(
            "Setting position (current={current}, target={target})",
            [
                "current" => $currentPosition,
                "target" => $position
            ]
        );

        if ($this->speed > 0) {
            $time = (int)ceil(abs($currentPosition - $position) / $this->speed);
            sleep($time);
        }

        $this->position = $position;
        if ($this->statusFile !== null){
            $this->fileSystem->filePutContents(
                $this->statusFile,
                (string)$this->position
            );
        }

        $this->logger->info(
            "Position set (position={position})",
            [
                "position" => $position,
            ]
        );
    }

    public function getPosition(): int
    {
        if ($this->statusFile !== null) {
            try {
                $this->position = intval(
                    $this->fileSystem->fileGetContents(
                        $this->statusFile
                    ),
                    10
                );
            } catch (\Exception $ex){}
        }
        return $this->position;
    }
}
