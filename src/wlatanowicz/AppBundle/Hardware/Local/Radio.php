<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use wlatanowicz\AppBundle\Data\Spectrum;
use wlatanowicz\AppBundle\Factory\SpectrumFactory;
use wlatanowicz\AppBundle\Hardware\RadioInterface;

class Radio implements RadioInterface
{
    /**
     * @var RadioReceiver
     */
    private $radioReceiver;

    /**
     * @var SpectrumFactory
     */
    private $spectrumFactory;

    /**
     * Radio constructor.
     * @param RadioReceiver $radioReceiver
     * @param SpectrumFactory $spectrumFactory
     */
    public function __construct(RadioReceiver $radioReceiver, SpectrumFactory $spectrumFactory)
    {
        $this->radioReceiver = $radioReceiver;
        $this->spectrumFactory = $spectrumFactory;
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
        return $this->spectrumFactory->fromReceiverOutput($dataArray);
    }
}
