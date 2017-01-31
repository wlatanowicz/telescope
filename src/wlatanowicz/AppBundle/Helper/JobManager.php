<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

use JMS\Serializer\SerializerInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;

class JobManager
{
    /**
     * @var string
     */
    private $currentJobId;

    /**
     * @var string
     */
    private $currentSessionId;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $logFile;

    /**
     * @var string
     */
    private $statusFile;

    /**
     * @var string
     */
    private $resultDir;

    /**
     * JobManager constructor.
     * @param string $logFile
     * @param string $statusFile
     * @param string $resultDir
     */
    public function __construct(
        SerializerInterface $serializer,
        FileSystem $fileSystem,
        string $logFile,
        string $statusFile,
        string $resultDir
    ) {
        $this->logFile = $logFile;
        $this->statusFile = $statusFile;
        $this->resultDir = $resultDir;
        $this->currentJobId = null;
        $this->currentSessionId = null;
    }

    public function startJob(string $jobId = null, string $sessionId = null)
    {
        $this->currentJobId = 'job' . date('YmdHis') . str_pad((string)rand(0, 999), 3, '0', STR_PAD_LEFT);
        $this->currentSessionId = 'sess' . date('Ymd');
    }

    public function saveCurrentJobResult(string $fileName, string $data)
    {
        $fullFileName = $this->getCurrentJobResultDirPath() . '/' . $fileName;
        $this->fileSystem->filePutContents($fullFileName, $data);
    }

    /**
     * @return string
     */
    public function getCurrentJobId(): string
    {
        return $this->currentJobId;
    }

    /**
     * @return string
     */
    public function getCurrentSessionId(): string
    {
        return $this->currentSessionId;
    }

    public function isJobActive(): bool
    {
        return $this->currentJobId !== null;
    }

    public function getCurrentJobLogFilePath(): string
    {
        return $this->getJobLogFilePath(
            $this->getCurrentJobId(),
            $this->getCurrentSessionId()
        );
    }

    public function getCurrentJobStatusFilePath(): string
    {
        return $this->getJobStatusFilePath(
            $this->getCurrentJobId(),
            $this->getCurrentSessionId()
        );
    }

    public function getCurrentJobResultDirPath(): string
    {
        return $this->getJobResultDirPath(
            $this->getCurrentJobId(),
            $this->getCurrentSessionId()
        );
    }

    public function getJobLogFilePath(string $jobId, string $sessionId): string
    {
        return $this->processDirName(
            $this->logFile,
            $jobId,
            $sessionId
        );
    }

    public function getJobStatusFilePath(string $jobId, string $sessionId): string
    {
        return $this->processDirName(
            $this->statusFile,
            $jobId,
            $sessionId
        );
    }

    public function getJobResultDirPath(string $jobId = null, string $sessionId): string
    {
        return $this->processDirName(
            $this->resultDir,
            $jobId,
            $sessionId
        );
    }

    private function processDirName(string $dir, string $jobId = null, string $sessionId = null): string
    {
        return strtr(
            $dir,
            [
                "{jobId}" => $jobId,
                "{sessionId}" => $sessionId,
            ]
        );
    }
}
