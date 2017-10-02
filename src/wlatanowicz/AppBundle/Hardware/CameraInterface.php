<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\BinaryImages;

interface CameraInterface
{
    const FORMAT_BOTH = 'both';
    const FORMAT_RAW = 'raw';
    const FORMAT_JPEG = 'jpeg';

    /**
     * @param float $time in seconds
     * @return BinaryImages
     */
    public function exposure(float $time): BinaryImages;

    public function setIso(int $iso);

    public function setFormat(string $format);

    public function getIso(): int;

    public function getFormat(): string;

    public function getBatteryLevel(): float;
}
