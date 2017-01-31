<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

class CameraExposeParams implements JobParamsInterface
{
    /**
     * @var string|null
     */
    private $cameraName;

    /**
     * @var int
     */
    private $time;

    /**
     * @var string|null
     */
    private $fileName;

    /**
     * CameraExposeParams constructor.
     * @param string|null $cameraName
     * @param int $time
     * @param string|null $fileName
     */
    public function __construct(
        string $cameraName = null,
        int $time,
        string $fileName = null
    ) {
        $this->cameraName = $cameraName;
        $this->time = $time;
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getCameraName(): string
    {
        return $this->cameraName;
    }

    public function hasCameraName(): bool
    {
        return $this->cameraName !== null;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return null|string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function hasFileName(): bool
    {
        return $this->fileName !== null;
    }

}
