<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

use JMS\Serializer\Annotation\Type;

class Coordinates
{
    /**
     * @var float
     * @Type("double")
     */
    private $rightAscension;

    /**
     * @var float;
     * @Type("double")
     */
    private $declination;

    /**
     * Coordinates constructor.
     * @param float $rightAscension
     * @param float $declination
     */
    public function __construct(float $rightAscension, float $declination)
    {
        $this->rightAscension = $rightAscension;
        $this->declination = $declination;
    }

    /**
     * @return float
     */
    public function getRightAscension(): float
    {
        return $this->rightAscension;
    }

    /**
     * @return float
     */
    public function getDeclination(): float
    {
        return $this->declination;
    }

    const FULL_SCALE_HEX = 0xFFFFFFFF;
    const FULL_SCALE_HEX_LENGTH = 8;
    const FULL_SCALE_HOURS = 24;
    const FULL_SCALE_ANGLE = 360;

    public function toString(): string
    {
        $ra = self::FULL_SCALE_HEX * ($this->getRightAscension() / self::FULL_SCALE_HOURS);
        $dec = self::FULL_SCALE_HEX * ($this->getDeclination() / self::FULL_SCALE_ANGLE);

        $raStr = str_pad(strtoupper(dechex($ra)), self::FULL_SCALE_HEX_LENGTH, '0', STR_PAD_LEFT);
        $decStr = str_pad(strtoupper(dechex($dec)), self::FULL_SCALE_HEX_LENGTH, '0', STR_PAD_LEFT);

        return $raStr . "," . $decStr;
    }

    public static function fromString(string $string): self
    {
        list($ra, $dec) = array_map('hexdec', explode(",", $string));

        return new self(
            ($ra / self::FULL_SCALE_HEX) * self::FULL_SCALE_HOURS,
            ($dec / self::FULL_SCALE_HEX) * self::FULL_SCALE_ANGLE
        );
    }
}