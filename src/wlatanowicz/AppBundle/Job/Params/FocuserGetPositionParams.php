<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

use JMS\Serializer\Annotation\Type;

class FocuserGetPositionParams implements JobParamsInterface
{
    /**
     * @var string|null
     * @Type("string")
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
