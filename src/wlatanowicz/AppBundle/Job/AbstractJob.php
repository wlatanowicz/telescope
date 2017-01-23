<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Data\JobStatus;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\JobParamsInterface;

abstract class AbstractJob
{
    /**
     * @var JobManager
     */
    protected $jobManager;

    /**
     * AbstractJob constructor.
     * @param JobManager $jobManager
     */
    public function __construct(JobManager $jobManager)
    {
        $this->jobManager = $jobManager;
    }

    public function start(JobParamsInterface $params, string $jobId = null, string $sessionId = null)
    {
        $method = 'execute';

        $this->jobManager->startJob($jobId, $sessionId);
        $this->{$method}($params);

        return new JobStatus();
    }

    public function getParamsClass(): string
    {
        $reflection = new \ReflectionMethod(get_class($this), "execute");
        $parameters = $reflection->getParameters();
        $clasname = $parameters[0]->getClass()->getName();
        return $clasname;
    }

    //protected abstract function execute(JobParamsInterface $params);
}
