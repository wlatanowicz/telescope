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

    abstract protected function getCameraModel(): string;

    protected function execGphoto(string $cmd): array
    {
        $model = $this->getCameraModel();
        $fullCmd = "{$this->bin} --camera=\"$model\" --quiet {$cmd}";
        return $this->process->exec($fullCmd);
    }

    protected function getCameraConfig(string $config): string
    {
        $output = $this->execGphoto("--get-config {$config}");
        return $this->getCurentSettingFromCommandOutput($output);
    }

    protected function setCameraConfigIndex(string $config, int $index)
    {
        $this->execGphoto("--set-config-index {$config}={$index}");
    }

    protected function setCameraConfig(string $config, string $value)
    {
        $this->execGphoto("--set-config {$config}={$value}");
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