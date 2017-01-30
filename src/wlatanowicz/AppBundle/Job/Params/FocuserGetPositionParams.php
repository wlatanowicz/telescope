<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

class FocuserGetPositionParams implements JobParamsInterface
{
    /**
     * @var string|null
     */
    private $focuserName;

    /**
     * FocuserSetPositionParams constructor.
     * @param null|string $focuserName
     */
    public function __construct(
        string $focuserName = null
    ) {
        $this->focuserName = $focuserName;
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
}
