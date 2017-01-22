<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

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
        $this->currentJobId = md5((string)(time().rand(0,999)));
        $this->currentSessionId = 'sess'.date('Ymd');
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

    public function getJobResultDirPath(string $jobId, string $sessionId): string
    {
        return $this->processDirName(
            $this->resultDir,
            $jobId,
            $sessionId
        );
    }

    private function processDirName(string $dir, string $jobId, string $sessionId): string
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
