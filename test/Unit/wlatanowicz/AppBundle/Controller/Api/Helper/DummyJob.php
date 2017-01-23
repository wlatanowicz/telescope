<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Api\Helper;

use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\AbstractJob;

class DummyJob extends AbstractJob
{
    /**
     * @var DummyJobResult
     */
    public $jobResult;

    /**
     * @var DummyJobParams
     */
    public $jobParams;

    protected function execute(DummyJobParams $params)
    {
        $this->jobParams = $params;
        return $this->jobResult;
    }
}
