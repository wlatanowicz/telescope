<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\HSV;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue;
use wlatanowicz\AppBundle\Data\RGB as RGBData;
use wlatanowicz\AppBundle\Data\Spectrum;
use wlatanowicz\AppBundle\Factory\RangedValue as RangedValueFactory;
use wlatanowicz\AppBundle\Factory\HSV as HSVFactory;

class RGB
{
    const CONVERSION_PRECISION = 5;

    public static function fromHSV(HSV $hsv): RGBData
    {
        $range = Range::ONE();
        $factory = new RangedValueFactory($range);

        $H = $factory->convert($hsv->getH())->getValue();
        $S = $factory->convert($hsv->getS())->getValue();
        $V = $factory->convert($hsv->getV())->getValue();

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
            new RangedValue(round($R, self::CONVERSION_PRECISION), $range),
            new RangedValue(round($G, self::CONVERSION_PRECISION), $range),
            new RangedValue(round($B, self::CONVERSION_PRECISION), $range)
        );
    }

    /**
     * @param RGBData[] $colors
     * @return RGBData
     */
    public static function fromCollection(array $colors): RGBData
    {
        $range = Range::ONE();
        $factory = new RangedValueFactory($range);
        $r = 0;
        $g = 0;
        $b = 0;

        foreach ($colors as $color) {
            $r += pow($factory->convert($color->getR())->getValue(), 2);
            $g += pow($factory->convert($color->getG())->getValue(), 2);
            $b += pow($factory->convert($color->getB())->getValue(), 2);
        }

        $r = pow(3.0 * $r / count($colors), 0.5);
        $g = pow(3.0 * $g / count($colors), 0.5);
        $b = pow(3.0 * $b / count($colors), 0.5);

        $r = $r > $range->getMax()
            ? $range->getMax()
            : $r;

        $g = $g > $range->getMax()
            ? $range->getMax()
            : $g;

        $b = $b > $range->getMax()
            ? $range->getMax()
            : $b;

        return new RGBData(
            new RangedValue($r, $range),
            new RangedValue($g, $range),
            new RangedValue($b, $range)
        );
    }

    public static function fromSpectrum(Spectrum $spectrum): RGBData
    {
        $hsvFactory = new HSVFactory($spectrum->getFrequencyRange(), $spectrum->getPowerRange());
        $rgbColors = [];
        foreach ($spectrum->getDataPoints() as $dataPoint) {
            $hsvColor = $hsvFactory->fromSpectrumPoint($dataPoint);
            $rgbColors[] = self::fromHSV($hsvColor);
        }

        return self::fromCollection($rgbColors);
    }
}
