<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Factory\TelescopeCoordinatesConverter;

class Telescope
{
    const SET_POSITION_COMMAND = 'r';
    const GET_POSITION_COMMAND = 'e';

    const DEFAULT_TOLERANCE_RA = 0.1;
    const DEFAULT_TOLERANCE_DEC = 0.1;

    const SETTLE_CHECK_WAIT = 100000;
    const SETTLE_WAIT = 100000;

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

    public function setPosition(
        Coordinates $coordinates,
        bool $wait = true,
        Coordinates $tolerance = null
    )
    {
        if ($tolerance == null) {
            $tolerance = new Coordinates(
                self::DEFAULT_TOLERANCE_DEC,
                self::DEFAULT_TOLERANCE_RA
            );
        }

        $positionAsString = TelescopeCoordinatesConverter::coordinatesToString($coordinates);

        $this->mount->sendCommand(self::SET_POSITION_COMMAND . $positionAsString);

        if ($wait) {
            do {
                usleep(self::SETTLE_CHECK_WAIT);

                $currentPosition = $this->getPosition();

                $decDiff = $coordinates->getDeclination() - $currentPosition->getDeclination();
                $raDiff = $coordinates->getRightAscension() - $currentPosition->getRightAscension();

                $positioned = abs($decDiff) <= abs($tolerance->getDeclination())
                    && abs($raDiff) <= abs($tolerance->getRightAscension());

            }while(!$positioned);

            usleep(self::SETTLE_WAIT);
        }

    }

    public function getPosition(): Coordinates
    {
        $response = $this->mount->sendCommand(self::GET_POSITION_COMMAND);
        return TelescopeCoordinatesConverter::stringToCoordinates($response);
    }
}
