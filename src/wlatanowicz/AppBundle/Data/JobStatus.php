<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

use JMS\Serializer\Annotation\Type;

class JobStatus
{
    const STATUS_PREPARED = 'prepared';
    const STATUS_RUNNING = 'running';
    const STATUS_FINISHED = 'finished';

    /**
     * @var string
     * @Type("string")
     */
    private $status;

    /**
     * @var \DateTime|null
     * @Type("DateTime")
     */
    private $startTime;

    /**
     * @var \DateTime|null
     * @Type("DateTime")
     */
    private $endTime;

    /**
     * @var string
     * @Type("string")
     */
    private $jobId;

    /**
     * @var string
     * @Type("string")
     */
    private $sessionId;

    /**
     * @var mixed
     */
    private $result;

    /**
     * JobStatus constructor.
     * @param string $jobId
     * @param string $sessionId
     */
    public function __construct(string $sessionId, string $jobId)
    {
        $this->jobId = $jobId;
        $this->sessionId = $sessionId;
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @param \DateTime|null $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @param \DateTime|null $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }
}
