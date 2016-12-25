<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Simulator;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\ImagickImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;

class Camera implements CameraInterface
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
     * @var string
     */
    private $imageName;

    /**
     * @var string
     */
    private $logPrefix;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var int
     */
    private $focusPoint;

    /**
     * @var float
     */
    private $focusSlope1;

    /**
     * @var float
     */
    private $focusSlope2;

    /**
     * Camera constructor.
     * @param FocuserInterface $focuser
     * @param FileSystem $fileSystem
     * @param string $imageName
     */
    public function __construct(
        FocuserInterface $focuser,
        FileSystem $fileSystem,
        string $imageName,
        LoggerInterface $logger,
        string $logPrefix
    ) {
        $this->focuser = $focuser;
        $this->fileSystem = $fileSystem;
        $this->imageName = $imageName;

        $this->logger = $logger;
        $this->logPrefix = $logPrefix;

        $this->focusPoint = 3421;
        $this->focusSlope1 = 0.5;
        $this->focusSlope2 = 0.8;
    }

    public function exposure(int $time): BinaryImage
    {
        $start = time();

        $this->logger->info(
            "Starting exposure (time={time}s)",
            [
                "prefix" => $this->logPrefix,
                "time" => $time,
            ]
        );

        $image = $this->getImage();

        $imagickImage = ImagickImage::fromBinaryImage($image);

        $this->blurImage($imagickImage);

        if ($time > 0) {
            $willLast = $time + self::SIM_DOWNLOAD_AND_PREPARE_TIME;
            $finish = time();
            $lasted = $finish - $start;
            if ($lasted < $willLast) {
                sleep($willLast - $lasted);
            }
        }

        $this->logger->info(
            "Finished exposure",
            [
                "prefix" => $this->logPrefix,
            ]
        );

        return new BinaryImage($imagickImage->getImageBlob(), "application/jpeg");
    }

    public function setIso(int $iso) {}

    public function setFormat(string $format) {}

    private function getImage(): BinaryImage
    {
        $path = __DIR__ . "/Resources/" . $this->imageName;
        $binary = $this->fileSystem->fileGetContents($path);
        return new BinaryImage(
            $binary,
            "image/jpeg"
        );
    }

    private function blurImage(ImagickImage $imagickImage)
    {
        $level = $this->getBlurLevel() / 6;
        if ($level > 0.01) {
            $imagickImage->blur($level, 20);
        }
    }

    private function getBlurLevel(): float
    {
        $currentFocuserPosition = $this->focuser->getPosition();
        $val = $currentFocuserPosition <= $this->focusPoint
            ? $this->focusSlope1 * $currentFocuserPosition - $this->focusSlope1 * $this->focusPoint
            : (-$this->focusSlope2) * $currentFocuserPosition + $this->focusSlope2 * $this->focusPoint;
        return -$val;
    }

}
