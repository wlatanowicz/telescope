<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Factory;

abstract class AbstractExposureTimeStringFactory
{
    const BULB = "Bulb";

    const COMPARE_ERROR = 0.00000001;

    static $SPEEDS = [
        "1/4000",
        "25/10",
        "32/10",
        "6",
        "15",
        "30",
    ];

    /**
     * @var FloatFromStringFactory
     */
    private $floatFromString;

    /**
     * SonyExposureTimeStringFactory constructor.
     * @param FloatFromStringFactory $floatFromString
     */
    public function __construct(FloatFromStringFactory $floatFromString)
    {
        $this->floatFromString = $floatFromString;
    }

    public function exposureStringFromFloat(float $time): string
    {
        $maxNonBulb = $this->floatFromString->floatFromString(static::$SPEEDS[count(static::$SPEEDS) - 1]);
        if ($time > $maxNonBulb) {
            return static::BULB;
        }

        foreach (static::$SPEEDS as $speed) {
            if ($this->equal($time, $speed)) {
                return $speed;
            }

            if ($this->less($time, $speed)) {
                return $speed;
            }
        }

        return static::BULB;
    }

    private function equal(float $timeAsFloat, string $timeAsString): bool
    {
        $timeFromString = $this->floatFromString->floatFromString($timeAsString);

        return abs($timeFromString - $timeAsFloat) <= static::COMPARE_ERROR;
    }

    private function less(float $timeAsFloat, string $timeAsString): bool
    {
        return $this->floatFromString->floatFromString($timeAsString) > $timeAsFloat;
    }
}
