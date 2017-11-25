<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use Psr\Log\LoggerInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Data\BinaryImages;
use wlatanowicz\AppBundle\Factory\NikonExposureTimeStringFactory;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Helper\Process;

class NikonCamera extends AbstractGphotoCamera
{
    /**
     * @var NikonExposureTimeStringFactory
     */
    private $exposureTimeStringFactory;

    private $gpio = 10;
    private $gpioActiveTime = 200000;

    public function __construct(
        Process $process,
        FileSystem $filesystem,
        NikonExposureTimeStringFactory $exposureTimeStringFactory,
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

        if ($timeAsString == NikonExposureTimeStringFactory::BULB) {
            $filesBefore = $this->listCameraFiles();

            $this->setCameraConfigIndex("capturemode", 3);
            $this->initGpio();
            $this->exposeViaGpio($time);

            $tries = 0;
            do {
                $tries++;
                if ($tries > 30) {
                    throw new \Exception('Cannot list images after exposure');
                }

                sleep(2);

                $filesAfter = $this->listCameraFiles();
            } while (count($filesAfter) <= count($filesBefore));

            $newFiles = array_diff($filesAfter, $filesBefore);

            foreach ($newFiles as $file) {
                $fileNum = $file + 1;
                $cmd = "--force-overwrite"
                    . " --get-file={$fileNum}"
                    . " --filename={$tempdir}/img.%C";
                $this->execGphoto($cmd);
            }
        } else {
            $cmd = "--force-overwrite"
                . " --capture-image-and-download"
                . " --filename={$tempdir}/img.%C";
            $this->execGphoto($cmd);
        }


        $images = [];
        $extensions = ['jpg', 'nef', 'JPG', 'NEF'];

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
            case 1600: $isoIdx = 9; break;
            case 1250: $isoIdx = 8; break;
            case 1000: $isoIdx = 7; break;
            case 800: $isoIdx = 6; break;
            case 640: $isoIdx = 5; break;
            case 500: $isoIdx = 4; break;
            case 400: $isoIdx = 3; break;
            case 320: $isoIdx = 2; break;
            case 250: $isoIdx = 1; break;
            default:
            case 200: $isoIdx = 0; break;
        }

        $this->setCameraConfigIndex('iso', $isoIdx);
    }

    private function setCameraSpeed(string $speed)
    {
        $this->setCameraConfig('shutterspeed2', $speed);
    }

    public function setFormat(string $format)
    {
        switch ($format) {
            case self::FORMAT_RAW: $formatIdx = 3; break;
            case self::FORMAT_JPEG: $formatIdx = 2; break;
            default:
            case self::FORMAT_BOTH: $formatIdx = 4; break;
        }

        $this->setCameraConfigIndex('imagequality', $formatIdx);
    }

    public function getIso(): int
    {
        return intval($this->getCameraConfig('iso'), 10);
    }

    public function getFormat(): string
    {
        $rawFormat = $this->getCameraConfig('imagequality');

        if ($rawFormat === "NEF+Basic") {
            $format = self::FORMAT_BOTH;
        } elseif ($rawFormat === "NEF (Raw)") {
            $format = self::FORMAT_RAW;
        } elseif ($rawFormat === "JPEG Basic" || $rawFormat === "JPEG Normal" || $rawFormat === "JPEG Fine") {
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
        return 'Nikon DSC D70 (PTP mode)';
    }

    private function sudoExec($cmd)
    {
        $this->process->exec("sudo bash -c \"{$cmd}\"");
    }

    private function initGpio()
    {
        if (!is_dir("/sys/class/gpio/")) {
            throw new NotFoundResourceException("RPi GPIO port not found");
        }
        if (!is_dir("/sys/class/gpio/gpio{$this->gpio}")) {
            $this->sudoExec("echo {$this->gpio} > /sys/class/gpio/export");
        }
        $this->sudoExec("echo out > /sys/class/gpio/gpio{$this->gpio}/direction");
    }

    private function driveGpio(int $state)
    {
        $this->sudoExec("echo $state > /sys/class/gpio/gpio{$this->gpio}/value");
    }

    private function exposeViaGpio(float $time)
    {
        $start = microtime(true);

        $this->driveGpio(1);
        usleep($this->gpioActiveTime);
        $this->driveGpio(0);

        $timeLeft = $time - (microtime(true) - $start);
        usleep((int)round($timeLeft * 1000000));

        $this->driveGpio(1);
        usleep($this->gpioActiveTime);
        $this->driveGpio(0);
    }

    private function listCameraFiles(): array
    {
        $files = $this->execGphoto("--list-files");
        return array_keys($files);
    }
}
