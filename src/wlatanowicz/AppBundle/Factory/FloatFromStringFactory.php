<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Factory;

class FloatFromStringFactory
{
    public function floatFromString(string $str): float
    {
        if (strpos($str, "/") !== false) {
            list($numerator, $denominator) = explode("/", $str, 2);
            return floatval($numerator) / floatval($denominator);
        } else {
            return floatval($str);
        }
    }
}
