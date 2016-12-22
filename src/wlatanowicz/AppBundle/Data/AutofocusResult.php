<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class AutofocusResult
{
    /**
     * @var AutofocusPoint[]
     */
    private $points;

    /**
     * @var AutofocusPoint
     */
    private $maximum;

    /**
     * AutofocusResult constructor.
     * @param AutofocusPoint[] $points
     * @param AutofocusPoint $maximum
     */
    public function __construct(AutofocusPoint $maximum, array $points)
    {
        $this->points = $points;
        $this->maximum = $maximum;
    }

    /**
     * @return AutofocusPoint[]
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * @return AutofocusPoint
     */
    public function getMaximum(): AutofocusPoint
    {
        return $this->maximum;
    }
}
