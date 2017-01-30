<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\FocuserGetPositionParams;

class FocuserGetPosition extends AbstractJob
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

    public function execute(FocuserGetPositionParams $params)
    {
        $focuserName = $params->hasFocuserName()
            ? $params->getFocuserName()
            : null;

        $focuser = $this->provider->getFocuser($focuserName);
        return $focuser->getPosition();
    }
}