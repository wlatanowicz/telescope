<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Factory;

class SonyExposureTimeStringFactory
{
    const BULB = "Bulb";

    const MAX_NON_BULB = 30;
    const MIN_BULB = 4;

    const COMPARE_ERROR = 0.00000001;

    static $SPEEDS = [
        "1/4000",
        "1/3200",
        "1/2500",
        "1/2000",
        "1/1250",
        "1/1000",
        "1/800",
        "1/640",
        "1/500",
        "1/400",
        "1/320",
        "1/250",
        "1/200",
        "1/200",
        "1/160",
        "1/125",
        "1/100",
        "1/80",
        "1/60",
        "1/40",
        "1/30",
        "1/25",
        "1/20",
        "1/15",
        "1/13",
        "1/10",
        "1/8",
        "1/6",
        "1/5",
        "1/4",
        "1/3",
        "0.4",
        "0.5",
        "0.6",
        "0.8",
        "1",
        "1.3",
        "1.6",
        "2",
        "2.5",
        "3.2",
        "4",
        "5",
        "6",
        "8",
        "10",
        "13",
        "15",
        "20",
        "25",
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
        if ($time > self::MAX_NON_BULB) {
            return self::BULB;
        }

        foreach (self::$SPEEDS as $speed) {
            if ($this->equal($time, $speed)) {
                return $speed;
            }

            if ($this->less($time, $speed)) {
                return $speed;
            }
        }

        return self::BULB;
    }

    private function equal(float $timeAsFloat, string $timeAsString): bool
    {
        $timeFromString = $this->floatFromString->floatFromString($timeAsString);

        return abs($timeFromString - $timeAsFloat) <= self::COMPARE_ERROR;
    }

    private function less(float $timeAsFloat, string $timeAsString): bool
    {
        return $this->floatFromString->floatFromString($timeAsString) > $timeAsFloat;
    }
}
