<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\Coordinates;

class Telescope
{
    const SET_POSITION_COMMAND = 'r';
    const GET_POSITION_COMMAND = 'e';

    const SETTLE_CHECK_WAIT = 1000000;

    /**
     * @var TelescopeMount
     */
    private $mount;

    /**
     * Telescope constructor.
     * @param TelescopeMount $mount
     */
    public function __construct(TelescopeMount $mount)
    {
        $this->mount = $mount;
    }

    public function setPosition(Coordinates $coordinates, bool $wait = true)
    {
        $positionAsString = $this->coordinatesToString($coordinates);
        $expectedResponse = $positionAsString."#";
        $this->mount->sendCommand(self::SET_POSITION_COMMAND . $positionAsString);
        while ($wait && $this->mount->sendCommand(self::GET_POSITION_COMMAND) != $expectedResponse) {
            usleep(self::SETTLE_CHECK_WAIT);
        }
    }

    private function coordinatesToString(Coordinates $coordinates): string
    {
        $ra = 0xffff * ($coordinates->getRightAscension() / 24);
        $dec = 0xffff * ($coordinates->getDeclination() / 360);

        $raStr = str_pad(strtoupper(dechex($ra)), 8, '0', STR_PAD_LEFT);
        $decStr = str_pad(strtoupper(dechex($dec)), 8, '0', STR_PAD_LEFT);
        
        return $raStr . "," . $decStr;
    }
}
