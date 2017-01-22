<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

class JobProvider
{
    /**
     * @var AbstractJob[]
     */
    private $jobs;

    /**
     * JobProvider constructor.
     * @param AbstractJob[] $jobs
     */
    public function __construct(array $jobs)
    {
        $this->jobs = $jobs;
    }

    public function getJob(string $name): AbstractJob
    {
        return $this->jobs[$name];
    }
}
