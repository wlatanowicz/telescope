<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\HSV;
use wlatanowicz\AppBundle\Data\RGB as RGBData;

class RGB
{
    const CONVERSION_PRECISION = 5;

    public static function fromHSV(HSV $hsv): RGBData
    {
        $H = $hsv->getH() / 360.0;
        $S = $hsv->getS();
        $V = $hsv->getV();

        //1
        $H *= 6;
        //2
        $I = floor($H);
        $F = $H - $I;
        //3
        $M = $V * (1 - $S);
        $N = $V * (1 - $S * $F);
        $K = $V * (1 - $S * (1 - $F));
        //4
        switch ($I) {
            case 0:
                list($R,$G,$B) = array($V,$K,$M);
                break;
            case 1:
                list($R,$G,$B) = array($N,$V,$M);
                break;
            case 2:
                list($R,$G,$B) = array($M,$V,$K);
                break;
            case 3:
                list($R,$G,$B) = array($M,$N,$V);
                break;
            case 4:
                list($R,$G,$B) = array($K,$M,$V);
                break;
            case 5:
            case 6: //for when $H=1 is given
                list($R,$G,$B) = array($V,$M,$N);
                break;
        }
        return new RGBData(
            round($R, self::CONVERSION_PRECISION),
            round($G, self::CONVERSION_PRECISION),
            round($B, self::CONVERSION_PRECISION)
        );
    }
}
