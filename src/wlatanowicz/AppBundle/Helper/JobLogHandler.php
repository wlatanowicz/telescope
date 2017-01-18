<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class JobLogHandler extends StreamHandler
{
    /**
     * @var JobManager
     */
    private $jobManager;

    public function __construct(
        JobManager $jobManager,
        int $level = Logger::DEBUG,
        bool $bubble = true,
        int $filePermission = null,
        bool $useLocking = false
    ) {
        parent::__construct('', $level, $bubble, $filePermission, $useLocking);
        $this->jobManager = $jobManager;
        $this->stream = null;
    }

    protected function write(array $record)
    {
        if ($this->jobManager->isJobActive()) {
            $this->url = $this->jobManager->getCurrentJobLogFilePath();
            parent::write($record);
        }
    }
}
