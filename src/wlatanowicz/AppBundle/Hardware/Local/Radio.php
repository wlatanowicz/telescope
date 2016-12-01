<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use wlatanowicz\AppBundle\Data\Spectrum;
use wlatanowicz\AppBundle\Hardware\RadioInterface;
use wlatanowicz\AppBundle\Factory\Spectrum as SpectrumFactory;

class Radio implements RadioInterface
{
    /**
     * @var RadioReceiver
     */
    private $radioReceiver;

    /**
     * Radio constructor.
     * @param RadioReceiver $radioReceiver
     */
    public function __construct(RadioReceiver $radioReceiver)
    {
        $this->radioReceiver = $radioReceiver;
    }

    public function getSpectrum(
        string $minFreq,
        string $maxFreq,
        string $binSize,
        int $integrationTime
    ): Spectrum
    {
        $dataArray = $this->radioReceiver->getPowerSpectrum(
            $minFreq,
            $maxFreq,
            $binSize,
            $integrationTime
        );
        return SpectrumFactory::fromReceiverOutput($dataArray);
    }
}
