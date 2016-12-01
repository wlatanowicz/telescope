<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\Spectrum;

interface RadioInterface
{
    public function getSpectrum(
        string $minFreq,
        string $maxFreq,
        string $binSize,
        int $integrationTime
    ): Spectrum;
}
