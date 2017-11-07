<?php

namespace wlatanowicz\AppBundle\Hardware\Local;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Helper\Process;

abstract class AbstractGphotoCamera implements CameraInterface
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * @var FileSystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $bin;

    /**
     * @var string
     */
    protected $temp;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        Process $process,
        FileSystem $filesystem,
        string $bin,
        string $temp,
        LoggerInterface $logger
    ) {
        $this->process = $process;
        $this->filesystem = $filesystem;
        $this->bin = $bin;
        $this->temp = $temp;

        $this->logger = $logger;
    }

    protected function getCameraConfig(string $config): string
    {
        $cmd = "{$this->bin}"
            . " --quiet"
            . " --get-config {$config}";

        $output = $this->process->exec($cmd);

        return $this->getCurentSettingFromCommandOutput($output);
    }

    protected function setCameraConfigIndex(string $config, int $index)
    {
        $cmd = "{$this->bin}"
            . " --quiet"
            . " --set-config-index {$config}={$index}";

        $this->process->exec($cmd);
    }

    protected function setCameraConfig(string $config, mixed $value)
    {
        $cmd = "{$this->bin}"
            . " --quiet"
            . " --set-config {$config}={$value}";

        $this->process->exec($cmd);
    }

    private function getCurentSettingFromCommandOutput(array $output): string
    {
        $search = "Current: ";
        foreach ($output as $line) {
            if (strpos($line, $search) === 0) {
                return substr($line, strlen($search));
            }
        }
        throw new \Exception("Cannot read current setting");
    }
}