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
    private $logsDir;

    /**
     * @var string
     */
    private $statusesDir;

    /**
     * @var string
     */
    private $resultsDir;

    /**
     * JobManager constructor.
     * @param string $logsDir
     * @param string $statusesDir
     * @param string $resultsDir
     */
    public function __construct($logsDir, $statusesDir, $resultsDir)
    {
        $this->logsDir = $logsDir;
        $this->statusesDir = $statusesDir;
        $this->resultsDir = $resultsDir;
        $this->currentJobId = null;
    }

    public function startJob(string $jobId = null, string $sessionId = null)
    {
        $this->currentJobId = md5((string)(time().rand(0,999)));
    }

    /**
     * @return string
     */
    public function getCurrentJobId(): string
    {
        return $this->currentJobId;
    }

    /**
     * @return bool
     */
    public function isJobActive(): bool
    {
        return $this->currentJobId !== null;
    }

    public function getCurrentJobLogFilePath(): string
    {
        return $this->getJobLogFilePath(
            $this->getCurrentJobId()
        );
    }

    public function getJobLogFilePath(string $jobId): string
    {
        return $this->logsDir . "/" . $jobId . ".log";
    }

    public function getJobStatusFilePath(string $jobId): string
    {
        return $this->statusesDir . "/" . $jobId . ".json";
    }

    public function getJobResultFilePath(string $jobId): string
    {
        return $this->resultsDir . "/" . $jobId . ".json";
    }
}
