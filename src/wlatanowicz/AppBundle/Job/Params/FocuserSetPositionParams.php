<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

use JMS\Serializer\Annotation\Type;

class FocuserSetPositionParams implements JobParamsInterface
{
    /**
     * @var string|null
     * @Type("string")
     */
    private $focuserName;

    /**
     * @var int
     * @Type("int")
     */
    private $position;

    /**
     * @var int|null
     * @Type("int")
     */
    private $tolerance;

    /**
     * FocuserSetPositionParams constructor.
     * @param null|string $focuserName
     * @param int $position
     * @param int|null $tolerance
     */
    public function __construct(
        string $focuserName = null,
        int $position,
        int $tolerance = null
    ) {
        $this->focuserName = $focuserName;
        $this->position = $position;
        $this->tolerance = $tolerance;
    }

    /**
     * @return string
     */
    public function getFocuserName(): string
    {
        return $this->focuserName;
    }

    public function hasFocuserName(): bool
    {
        return $this->focuserName !== null;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getTolerance(): int
    {
        return $this->tolerance;
    }

    public function hasTolerance(): bool
    {
        return $this->tolerance !== null;
    }
}
