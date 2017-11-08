<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\BinaryImages;
use wlatanowicz\AppBundle\Factory\SonyExposureTimeStringFactory;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Helper\Process;

class SonyCamera extends AbstractGphotoCamera
{
    /**
     * @var SonyExposureTimeStringFactory
     */
    private $exposureTimeStringFactory;

    public function __construct(
        Process $process,
        FileSystem $filesystem,
        SonyExposureTimeStringFactory $exposureTimeStringFactory,
        string $bin,
        string $temp,
        LoggerInterface $logger
    ) {
        parent::__construct($process, $filesystem, $bin, $temp, $logger);
        $this->exposureTimeStringFactory = $exposureTimeStringFactory;
    }

    public function exposure(float $time): BinaryImages
    {
        $timeAsString = $this->exposureTimeStringFactory->exposureStringFromFloat($time);
        $this->logger->info(
            "Setting camera speed (speed={speed})",
            [
                "speed" => $timeAsString,
            ]
        );
        $this->setCameraSpeed($timeAsString);

        $this->logger->info(
            "Starting exposure (time={time}s)",
            [
                "time" => $timeAsString,
            ]
        );

        $tempdir = $this->filesystem->tempName($this->temp);
        $this->filesystem->unlink($tempdir);
        $this->filesystem->mkdir($tempdir);

        if ($timeAsString == SonyExposureTimeStringFactory::BULB) {
            $cmd = "--force-overwrite"
                . " --set-config capture=on"
                . " --wait-event={$time}s"
                . " --set-config capture=off"
                . " --wait-event-and-download=10s"
                . " --filename={$tempdir}/img.%C";
        } else {
            $cmd = "--force-overwrite"
                . " --capture-image-and-download"
                . " --filename={$tempdir}/img.%C";
        }

        $this->execGphoto($cmd);

        $images = [];
        $extensions = ['JPG', 'ARW'];

        foreach ($extensions as $extension) {
            $file = $tempdir . "/img." . $extension;
            if ($this->filesystem->fileExists($file)) {
                $data = $this->filesystem->fileGetContents($file);
                $this->filesystem->unlink($file);
                $images[] = new BinaryImage($data);
            }
        }

        $this->logger->info("Finished exposure");

        return new BinaryImages($images);
    }

    public function setIso(int $iso)
    {
        switch ($iso) {
            case 3200: $isoIdx = 7; break;
            case 1600: $isoIdx = 6; break;
            case 800: $isoIdx = 5; break;
            case 400: $isoIdx = 4; break;
            case 200: $isoIdx = 3; break;
            default:
            case 100: $isoIdx = 2; break;
        }

        $this->setCameraConfigIndex('iso', $isoIdx);
    }

    private function setCameraSpeed(string $speed)
    {
        $this->setCameraConfig('shutterspeed', $speed);
    }

    public function setFormat(string $format)
    {
        switch ($format) {
            case self::FORMAT_RAW: $formatIdx = 2; break;
            case self::FORMAT_JPEG: $formatIdx = 1; break;
            default:
            case self::FORMAT_BOTH: $formatIdx = 3; break;
        }

        $this->setCameraConfigIndex('imagequality', $formatIdx);
    }

    public function getIso(): int
    {
        return intval($this->getCameraConfig('iso'));
    }

    public function getFormat(): string
    {
        $rawFormat = $this->getCameraConfig('imagequality');

        if ($rawFormat === "RAW+JPEG") {
            $format = self::FORMAT_BOTH;
        } elseif ($rawFormat === "RAW") {
            $format = self::FORMAT_RAW;
        } elseif ($rawFormat === "Standard" || $rawFormat === "Fine") {
            $format = self::FORMAT_JPEG;
        } else {
            throw new \Exception("Unknown image format: ".$rawFormat);
        }

        return $format;
    }

    public function getBatteryLevel(): float
    {
        return floatval($this->getCameraConfig('batterylevel'));
    }

    protected function getCameraModel(): string
    {
        return 'Sony SLT-A58 (Control)';
    }
}
