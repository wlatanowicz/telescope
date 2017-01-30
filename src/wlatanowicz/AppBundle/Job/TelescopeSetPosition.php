<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Hardware\Provider\TelescopeProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\TelescopeSetPositionParams;

class TelescopeSetPosition extends AbstractJob
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

    public function execute(TelescopeSetPositionParams $params)
    {
        $telescopeName = $params->hasTelescopeName()
            ? $params->getTelescopeName()
            : null;

        $telescope = $this->provider->getTelescope($telescopeName);

        $tolerance = $params->hasTolerance()
            ? $params->getTolerance()
            : null;

        $coordinates = $params->getCoordinates();

        $telescope->setPosition(
            $coordinates,
            true,
            $tolerance
        );
    }

}