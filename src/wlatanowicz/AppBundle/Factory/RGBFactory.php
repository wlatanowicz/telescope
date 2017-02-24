<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Factory;

use wlatanowicz\AppBundle\Data\HSV;
use wlatanowicz\AppBundle\Data\Range;
use wlatanowicz\AppBundle\Data\RangedValue;
use wlatanowicz\AppBundle\Data\RGB;
use wlatanowicz\AppBundle\Data\Spectrum;

class RGBFactory
{
    /**
     * @var int
     */
    private $conversionPrecision;

    /**
     * @var HSVFactory
     */
    private $hsvFactory;

    /**
     * RGBFactory constructor.
     * @param int $conversionPrecision
     * @param HSVFactory $hsvFactory
     */
    public function __construct(HSVFactory $hsvFactory, int $conversionPrecision = 5)
    {
        $this->conversionPrecision = $conversionPrecision;
        $this->hsvFactory = $hsvFactory;
    }

    /**
     * @param HSV $hsv
     * @return RGB
     */
    public function fromHSV(HSV $hsv): RGB
    {
        $range = Range::ONE();
        $H = $hsv->getH()->inRange($range)->getValue();
        $S = $hsv->getS()->inRange($range)->getValue();
        $V = $hsv->getV()->inRange($range)->getValue();

        $R = 0;
        $G = 0;
        $B = 0;

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
        return new RGB(
            new RangedValue(round($R, $this->conversionPrecision), $range),
            new RangedValue(round($G, $this->conversionPrecision), $range),
            new RangedValue(round($B, $this->conversionPrecision), $range)
        );
    }

    /**
     * @param RGB[] $colors
     * @return RGB
     */
    public function fromCollection(array $colors): RGB
    {
        $range = Range::ONE();
        $r = 0;
        $g = 0;
        $b = 0;

        foreach ($colors as $color) {
            $r += pow($color->getR()->inRange($range)->getValue(), 2);
            $g += pow($color->getG()->inRange($range)->getValue(), 2);
            $b += pow($color->getB()->inRange($range)->getValue(), 2);
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

        return new RGB(
            new RangedValue($r, $range),
            new RangedValue($g, $range),
            new RangedValue($b, $range)
        );
    }

    public function fromSpectrum(Spectrum $spectrum): RGB
    {
        $rgbColors = [];
        foreach ($spectrum->getDataPoints() as $dataPoint) {
            $hsvColor = $this->hsvFactory->fromSpectrumPoint($dataPoint, $spectrum->getFrequencyRange(), $spectrum->getPowerRange());
            $rgbColors[] = self::fromHSV($hsvColor);
        }

        return self::fromCollection($rgbColors);
    }
}
