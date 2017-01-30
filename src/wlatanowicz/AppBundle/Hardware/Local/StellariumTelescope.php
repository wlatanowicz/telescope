<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\TelescopeInterface;

class StellariumTelescope implements TelescopeInterface
{
    const DEFAULT_TOLERANCE_RA = 0.01;
    const DEFAULT_TOLERANCE_DEC = 0.01;

    const SETTLE_CHECK_WAIT = 1000000; // 1 sec
    const SETTLE_WAIT = 1000000; // 1 sec

    /**
     * @var string
     */
    private $server;

    /**
     * @var int
     */
    private $port;

    /**
     * StellariumTelescope constructor.
     * @param string $server
     * @param int $port
     */
    public function __construct(string $server, int $port)
    {
        $this->server = $server;
        $this->port = $port;
    }

    public function setPosition(
        Coordinates $coordinates,
        bool $wait = true,
        Coordinates $tolerance = null
    ) {
        $ra = ($coordinates->getRightAscension() / 12) * 0x80000000;
        $dec = $this->signed2unsigned((int)(($coordinates->getDeclination() / 90) * 0x40000000));

        $data_raw = pack(
            "vvNNVV",
            20,
            0,
            0,
            0,
            $ra,
            $dec
        );

        $fd = fsockopen($this->server, $this->port);
        fwrite($fd, $data_raw);
        fclose($fd);

        if ($tolerance == null) {
            $tolerance = new Coordinates(
                self::DEFAULT_TOLERANCE_DEC,
                self::DEFAULT_TOLERANCE_RA
            );
        }

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
        $fd = fsockopen($this->server, $this->port);

        $size_raw = fread($fd, 2);

        $size = unpack("vsize", $size_raw)['size'] - 2;

        $data_raw = fread($fd, $size);

        $data = unpack("vtype/N2time/Vra/Vdec/Nstatus", $data_raw);

        $ra = 12 * $data['ra'] / 0x80000000;
        $dec = 90 * $this->unsigned2signed((int)($data['dec'])) / 0x40000000;

        fclose($fd);

        return new Coordinates(
            $ra,
            $dec
        );
    }

    private function signed2unsigned(int $num): int
    {
        if ($num < 0)
        {
            $num += 0x100000000;
        }
        return $num;
    }

    private function unsigned2signed(int $num): int
    {
        if ($num > 0x7FFFFFFF)
        {
            $num -= 0x100000000;
        }
        return $num;
    }

}
