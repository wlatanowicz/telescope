<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Provider\TelescopeProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\TelescopeGetPositionParams;

class TelescopeGetPosition extends AbstractJob
{
    /**
     * @var TelescopeProvider
     */
    private $provider;

    /**
     * TelescopeSetPosition constructor.
     * @param JobManager $jobManager
     * @param TelescopeProvider $provider
     */
    public function __construct(
        JobManager $jobManager,
        TelescopeProvider $provider
    ) {
        parent::__construct($jobManager);
        $this->provider = $provider;
    }

    protected function execute(TelescopeGetPositionParams $params): Coordinates
    {
        $telescopeName = $params->hasTelescopeName()
            ? $params->getTelescopeName()
            : null;

        $telescope = $this->provider->getTelescope($telescopeName);

        return $telescope->getPosition();
    }
}