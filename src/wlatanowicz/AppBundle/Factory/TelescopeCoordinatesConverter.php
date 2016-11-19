<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\Coordinates;

class TelescopeCoordinatesConverter
{
    const FULL_SCALE_HEX = 0xFFFFFFFF;
    const FULL_SCALE_HEX_LENGTH = 8;
    const FULL_SCALE_HOURS = 24;
    const FULL_SCALE_ANGLE = 360;

    public static function coordinatesToString(Coordinates $coordinates): string
    {
        $ra = self::FULL_SCALE_HEX * ($coordinates->getRightAscension() / self::FULL_SCALE_HOURS);
        $dec = self::FULL_SCALE_HEX * ($coordinates->getDeclination() / self::FULL_SCALE_ANGLE);

        $raStr = str_pad(strtoupper(dechex($ra)), self::FULL_SCALE_HEX_LENGTH, '0', STR_PAD_LEFT);
        $decStr = str_pad(strtoupper(dechex($dec)), self::FULL_SCALE_HEX_LENGTH, '0', STR_PAD_LEFT);

        return $raStr . "," . $decStr;
    }

    public static function stringToCoordinates(string $string): Coordinates
    {
        list($ra, $dec) = array_map('hexdec', explode(",", $string));

        return new Coordinates(
            ($ra / self::FULL_SCALE_HEX) * self::FULL_SCALE_HOURS,
            ($dec / self::FULL_SCALE_HEX) * self::FULL_SCALE_ANGLE
        );
    }
}
