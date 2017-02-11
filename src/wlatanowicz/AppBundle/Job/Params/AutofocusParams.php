<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

use JMS\Serializer\Annotation\Type;

class AutofocusParams implements JobParamsInterface
{
    /**
     * @var string|null
     * @Type("string")
     */
    private $cameraName;

    /**
     * @var string|null
     * @Type("string")
     */
    private $focuserName;

    /**
     * @var string|null
     * @Type("string")
     */
    private $measureName;

    /**
     * @var int
     * @Type("int")
     */
    private $min;

    /**
     * @var int
     * @Type("int")
     */
    private $max;

    /**
     * @var int
     * @Type("int")
     */
    private $time;

    /**
     * @var int
     * @Type("int")
     */
    private $partials;

    /**
     * @var int
     * @Type("int")
     */
    private $iterations;

    /**
     * @var int[]
     * @Type("array<int>")
     */
    private $tries;

    /**
     * @var int
     * @Type("int")
     */
    private $radius;

    /**
     * @var int|null
     * @Type("int")
     */
    private $x;

    /**
     * @var int|null
     * @Type("int")
     */
    private $y;

    /**
     * @var string|null
     * @Type("string")
     */
    private $reportFile;

    /**
     * AutofocusParams constructor.
     * @param null|string $cameraName
     * @param null|string $focuserName
     * @param null|string $measureName
     * @param int $min
     * @param int $max
     * @param int $time
     * @param int $partials
     * @param int $iterations
     * @param \int[] $tries
     * @param int $radius
     * @param int|null $x
     * @param int|null $y
     * @param null|string $reportFile
     */
    public function __construct(
        string $cameraName = null,
        string $focuserName = null,
        string $measureName = null,
        int $min,
        int $max,
        int $time,
        int $partials,
        int $iterations,
        array $tries,
        int $radius,
        int $x = null,
        int $y = null,
        string $reportFile = null
    ) {
        $this->cameraName = $cameraName;
        $this->focuserName = $focuserName;
        $this->measureName = $measureName;
        $this->min = $min;
        $this->max = $max;
        $this->time = $time;
        $this->partials = $partials;
        $this->iterations = $iterations;
        $this->tries = $tries;
        $this->radius = $radius;
        $this->x = $x;
        $this->y = $y;
        $this->reportFile = $reportFile;
    }

    /**
     * @return bool
     */
    public function hasCameraName(): bool
    {
        return $this->cameraName !== null;
    }

    /**
     * @return bool
     */
    public function hasFocuserName(): bool
    {
        return $this->focuserName !== null;
    }

    /**
     * @return bool
     */
    public function hasMeasureName(): bool
    {
        return $this->measureName !== null;
    }

    /**
     * @return string
     */
    public function getCameraName(): string
    {
        return $this->cameraName;
    }

    /**
     * @return string
     */
    public function getFocuserName(): string
    {
        return $this->focuserName;
    }

    /**
     * @return string
     */
    public function getMeasureName(): string
    {
        return $this->measureName;
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getPartials(): int
    {
        return $this->partials;
    }

    /**
     * @return int
     */
    public function getIterations(): int
    {
        return $this->iterations;
    }

    /**
     * @return int[]
     */
    public function getTries(): array
    {
        return $this->tries;
    }

    /**
     * @return int
     */
    public function getRadius(): int
    {
        return $this->radius;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @return bool
     */
    public function hasX(): bool
    {
        return $this->x !== null;
    }

    /**
     * @return bool
     */
    public function hasY(): bool
    {
        return $this->y !== null;
    }

    /**
     * @return string
     */
    public function getReportFile(): string
    {
        return $this->reportFile;
    }

    /**
     * @return bool
     */
    public function hasReportFile(): bool
    {
        return $this->reportFile !== null;
    }
}