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

class SimpleCamera implements CameraInterface
{
    const SIM_DOWNLOAD_AND_PREPARE_TIME = 4;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImagickImageFactory
     */
    private $imagickImageFactory;

    /**
     * Camera constructor.
     * @param FocuserInterface $focuser
     * @param FileSystem $fileSystem
     * @param string $imageName
     */
    public function __construct(
        FileSystem $fileSystem,
        ImagickImageFactory $imagickImageFactory,
        string $imageName,
        LoggerInterface $logger
    ) {
        $this->fileSystem = $fileSystem;
        $this->imageName = $imageName;
        $this->logger = $logger;
        $this->imagickImageFactory = $imagickImageFactory;
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

        $image = $this->getImage();

        $imagickImage = $this->imagickImageFactory->fromBinaryImage($image);

        if ($time > 0) {
            $willLast = $time + self::SIM_DOWNLOAD_AND_PREPARE_TIME;
            $finish = time();
            $lasted = $finish - $start;
            if ($lasted < $willLast) {
                sleep((int)round($willLast - $lasted));
            }
        }

        $this->logger->info("Finished exposure");

        return new BinaryImages([
            new BinaryImage($imagickImage->getImageBlob(), BinaryImage::MIMETYPE_JPEG)
        ]);
    }

    public function setIso(int $iso)
    {
    }

    public function setFormat(string $format)
    {
    }

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

    private function getImage(): BinaryImage
    {
        $path = __DIR__ . "/Resources/" . $this->imageName;
        $binary = $this->fileSystem->fileGetContents($path);
        return new BinaryImage(
            $binary,
            BinaryImage::MIMETYPE_JPEG
        );
    }
}
