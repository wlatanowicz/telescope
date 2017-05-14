<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

use JMS\Serializer\SerializerInterface;
use wlatanowicz\AppBundle\Data\JobStatus;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;

class JobManager
{
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
     * @var JobStatus
     */
    private $currentStatus;

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
        $this->serializer = $serializer;
        $this->fileSystem = $fileSystem;
        $this->logFile = $logFile;
        $this->statusFile = $statusFile;
        $this->resultDir = $resultDir;
        $this->currentStatus = null;
    }

    public function startJob(string $jobId = null, string $sessionId = null)
    {
        $jobId = 'job' . date('YmdHis') . str_pad((string)rand(0, 999), 3, '0', STR_PAD_LEFT);
        $sessionId = 'sess' . date('Ymd');

        $this->currentStatus = new JobStatus($sessionId, $jobId);
        $this->currentStatus->setStatus(JobStatus::STATUS_RUNNING);
        $this->currentStatus->setStartTime(new \DateTime());
        $this->storeCurrentStatus();
    }

    public function finishJob($result = null)
    {
        $this->currentStatus->setEndTime(new \DateTime());
        $this->currentStatus->setStatus(JobStatus::STATUS_FINISHED);
        $this->currentStatus->setResult($result);
        $this->storeCurrentStatus();
    }

    public function saveCurrentJobResult(string $fileName, string $data)
    {
        $fullFileName = $this->getCurrentJobResultDirPath() . '/' . $fileName;
        $this->fileSystem->filePutContents($fullFileName, $data);
    }

    public function getCurrentJobStatus(): JobStatus
    {
        return $this->currentStatus;
    }

    /**
     * @return string
     */
    public function getCurrentJobId(): string
    {
        return $this->currentStatus->getJobId();
    }

    /**
     * @return string
     */
    public function getCurrentSessionId(): string
    {
        return $this->currentStatus->getSessionId();
    }

    public function isJobActive(): bool
    {
        return $this->currentStatus !== null;
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

    private function storeCurrentStatus()
    {
        $json = $this->serializer->serialize($this->currentStatus, 'json');
        $this->fileSystem->filePutContents(
            $this->getJobStatusFilePath(
                $this->getCurrentJobId(),
                $this->getCurrentSessionId()
            ),
            $json
        );
    }
}
