<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\BinaryImages;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;

class RealCamera implements CameraInterface
{
    const SIM_DOWNLOAD_AND_PREPARE_TIME = 4;

    /**
     * @var FocuserInterface
     */
    private $focuser;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    const MIN_POSITION = 0;
    const MAX_POSITION = 859;

    /**
     * Camera constructor.
     * @param FocuserInterface $focuser
     * @param FileSystem $fileSystem
     */
    public function __construct(
        FocuserInterface $focuser,
        FileSystem $fileSystem,
        LoggerInterface $logger
    ) {
        $this->focuser = $focuser;
        $this->fileSystem = $fileSystem;
        $this->logger = $logger;
    }

    public function exposure(float $time): BinaryImages
    {
        $start = time();

        $this->logger->info(
            "Starting exposure (time={time}s)",
            [
                "time" => $time,
            ]
        );

        $image = $this->getImage(
            $this->focuser->getPosition()
        );

        if ($time > 0) {
            $willLast = $time + self::SIM_DOWNLOAD_AND_PREPARE_TIME;
            $finish = time();
            $lasted = $finish - $start;
            if ($lasted < $willLast) {
                sleep((int)round($willLast - $lasted));
            }
        }

        $this->logger->info("Finished exposure");

        return new BinaryImages([$image]);
    }

    public function setIso(int $iso) {}

    public function setFormat(string $format) {}

    public function getIso(): int
    {
        return 100;
    }

    public function getFormat(): string
    {
        return self::FORMAT_JPEG;
    }

    public function getBatteryLevel(): float
    {
        return rand(0, 1000) / 10;
    }

    private function getImage(int $focusPoint): BinaryImage
    {
        if ($focusPoint < self::MIN_POSITION) {
            $focusPoint = self::MIN_POSITION;
        }
        if ($focusPoint > self::MAX_POSITION) {
            $focusPoint = self::MAX_POSITION;
        }

        $filename =  'focus-1' . str_pad((string)$focusPoint, 4, '0', STR_PAD_LEFT) . ".jpg";
        $path = __DIR__ . "/Resources/RealCamera/" .$filename;
        $binary = $this->fileSystem->fileGetContents($path);
        return new BinaryImage(
            $binary,
            BinaryImage::MIMETYPE_JPEG
        );
    }
}
