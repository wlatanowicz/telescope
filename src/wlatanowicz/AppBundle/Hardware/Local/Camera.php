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

    public function exposure(int $time)
    {
        $tempfile = $this->filesystem->tempName($this->temp);
        $cmd = "{$this->bin}"
            . " --set-config capture=on"
            . " --wait-event={$time}s"
            . " --set-config capture=off"
            . " --wait-event-and-download=10s"
            . " --filename={$tempfile}";

        $this->process->exec($cmd);

        $data = $this->filesystem->fileGetContents($tempfile);
        $this->filesystem->unlink($tempfile);

        return new BinaryImage($data);
    }
}
