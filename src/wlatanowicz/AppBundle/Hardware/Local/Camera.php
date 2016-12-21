<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Local;

use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Helper\Process;

class Camera implements CameraInterface
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @var FileSystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $bin;

    /**
     * @var string
     */
    private $temp;

    public function __construct(Process $process, FileSystem $filesystem, string $bin, string $temp)
    {
        $this->process = $process;
        $this->filesystem = $filesystem;
        $this->bin = $bin;
        $this->temp = $temp;
    }

    public function exposure(int $time): BinaryImage
    {
        $this->setBulb();

        $tempfile = $this->filesystem->tempName($this->temp);
        $this->filesystem->unlink($tempfile);

        $cmd = "{$this->bin}"
            . " --quiet"
            . " --force-overwrite"
            . " --set-config capture=on"
            . " --wait-event={$time}s"
            . " --set-config capture=off"
            . " --wait-event-and-download=10s"
            . " --filename={$tempfile}";

        $this->process->exec($cmd);

        $data = $this->filesystem->fileGetContents($tempfile);
        $this->filesystem->unlink($tempfile);

        $mimetype = null;
        return new BinaryImage($data, $mimetype);
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
        $cmd = "{$this->bin}"
            . " --quiet"
            . " --set-config-index iso={$isoIdx}";

        $this->process->exec($cmd);
    }

    private function setBulb()
    {
        $cmd = "{$this->bin}"
            . " --quiet"
            . " --set-config shutterspeed=Bulb";

        $this->process->exec($cmd);
    }

    public function setFormat(string $format)
    {
        switch ($format) {
            case self::FORMAT_RAW: $formatIdx = 2; break;
            default:
            case self::FORMAT_JPEG: $formatIdx = 1; break;
        }

        $cmd = "{$this->bin}"
            . " --quiet"
            . " --set-config-index imagequality={$formatIdx}";

        $this->process->exec($cmd);
    }
}
