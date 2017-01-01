<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware;

use wlatanowicz\AppBundle\Data\BinaryImage;

interface CameraInterface
{
    const FORMAT_RAW = 'raw';
    const FORMAT_JPEG = 'jpeg';

    /**
     * @param int $time in seconds
     * @return BinaryImage
     */
    public function exposure(int $time): BinaryImage;

    public function setIso(int $iso);

    public function setFormat(string $format);

    public function getIso(): int;

    public function getFormat(): string;
}
