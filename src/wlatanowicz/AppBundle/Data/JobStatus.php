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

    public function setResult($result)
    {
        $this->result = $result;
    }
}
