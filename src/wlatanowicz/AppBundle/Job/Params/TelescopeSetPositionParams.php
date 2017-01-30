<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job\Params;

use wlatanowicz\AppBundle\Data\Coordinates;

class TelescopeSetPositionParams implements JobParamsInterface
{
    /**
     * @var string|null
     */
    private $telescopeName;

    /**
     * @var Coordinates
     */
    private $coordinates;

    /**
     * @var Coordinates|null
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
