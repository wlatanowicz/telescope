<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\TelescopeInterface;

class Telescope implements TelescopeInterface
{
    /**
     * @var Coordinates
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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Telescope constructor.
     * @param float $speed
     * @param null|string $statusFile
     * @param LoggerInterface $logger
     * @param FileSystem $fileSystem
     */
    public function __construct($speed, $statusFile, LoggerInterface $logger, FileSystem $fileSystem, SerializerInterface $serializer)
    {
        $this->speed = $speed;
        $this->statusFile = $statusFile;
        $this->logger = $logger;
        $this->fileSystem = $fileSystem;
        $this->serializer = $serializer;

        $this->position = new Coordinates(0, 0);
    }

    public function setPosition(
        Coordinates $coordinates,
        bool $wait = true,
        Coordinates $tolerance = null
    ) {
        $this->position = $coordinates;
        try {
            $this->fileSystem->filePutContents(
                $this->statusFile,
                $this->serializer->serialize(
                    $this->position,
                    'json'
                )
            );
        } catch (\Exception $ex) {}
    }

    public function getPosition(): Coordinates
    {
        if ($this->statusFile !== null) {
            try {
                $this->position = $this->serializer->deserialize(
                    $this->fileSystem->fileGetContents(
                        $this->statusFile
                    ),
                    Coordinates::class,
                    'json'
                );
            } catch (\Exception $ex) {}
        }
        return $this->position;
    }
}
