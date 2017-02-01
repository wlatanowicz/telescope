<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\FocuserSetPositionParams;

class FocuserSetPosition extends AbstractJob
{
    /**
     * @var FocuserProvider
     */
    private $provider;

    /**
     * TelescopeSetPosition constructor.
     * @param JobManager $jobManager
     * @param FocuserProvider $provider
     */
    public function __construct(
        JobManager $jobManager,
        FocuserProvider $provider
    ) {
        parent::__construct($jobManager);
        $this->provider = $provider;
    }

    protected function execute(FocuserSetPositionParams $params)
    {
        $focuserName = $params->hasFocuserName()
            ? $params->getFocuserName()
            : null;

        $focuser = $this->provider->getFocuser($focuserName);

        $tolerance = $params->hasTolerance()
            ? $params->getTolerance()
            : null;

        $position = $params->getPosition();

        $focuser->setPosition(
            $position,
            true,
            $tolerance
        );
    }

}