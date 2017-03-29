<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

use wlatanowicz\AppBundle\Data\Coordinates;
use JMS\Serializer\Annotation\Type;

class TelescopeGetPositionParams implements JobParamsInterface
{
    /**
     * @var string|null
     * @Type("string")
     */
    private $telescopeName;

    /**
     * TelescopeSetPositionParams constructor.
     * @param string $telescopeName
     */
    public function __construct(string $telescopeName = null)
    {
        $this->telescopeName = $telescopeName;
    }

    /**
     * @return string
     */
    public function getTelescopeName(): string
    {
        return $this->telescopeName;
    }

    public function hasTelescopeName(): bool
    {
        return $this->telescopeName !== null;
    }
}
