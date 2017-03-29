<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

use wlatanowicz\AppBundle\Data\Coordinates;
use JMS\Serializer\Annotation\Type;

class TelescopeSetPositionParams implements JobParamsInterface
{
    /**
     * @var string|null
     * @Type("string")
     */
    private $telescopeName;

    /**
     * @var Coordinates
     * @type("wlatanowicz\AppBundle\Data\Coordinates")
     */
    private $coordinates;

    /**
     * @var Coordinates|null
     * @type("wlatanowicz\AppBundle\Data\Coordinates")
     */
    private $tolerance;

    /**
     * TelescopeSetPositionParams constructor.
     * @param string $telescopeName
     * @param Coordinates $coordinates
     * @param Coordinates $tolerance
     */
    public function __construct(
        string $telescopeName = null,
        Coordinates $coordinates,
        Coordinates $tolerance = null
    ) {
        $this->telescopeName = $telescopeName;
        $this->coordinates = $coordinates;
        $this->tolerance = $tolerance;
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

    /**
     * @return Coordinates
     */
    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    /**
     * @return Coordinates
     */
    public function getTolerance(): Coordinates
    {
        return $this->tolerance;
    }

    public function hasTolerance(): bool
    {
        return $this->tolerance !== null;
    }

}
