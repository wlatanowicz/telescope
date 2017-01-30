<?php

namespace Unit\wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Hardware\FocuserInterface;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\FocuserSetPosition;
use wlatanowicz\AppBundle\Job\Params\FocuserSetPositionParams;

class FocuserSetPositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FocuserInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $focuser;

    /**
     * @var FocuserProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $provider;

    /**
     * @var JobManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobManager;

    /**
     * @var FocuserSetPosition
     */
    private $job;

    /**
     * @before
     */
    public function prepare()
    {
        $this->focuser = $this->createMock(FocuserInterface::class);
        $this->provider = $this->createMock(FocuserProvider::class);
        $this->jobManager = $this->createMock(JobManager::class);

        $this->job = new FocuserSetPosition($this->jobManager, $this->provider);
    }

    /**
     * @test
     */
    public function itShouldSetPosition()
    {
        $telescopeName = 'focuser';
        $position = rand(-1000, 1000);

        $this->provider
            ->expects($this->once())
            ->method('getFocuser')
            ->with($telescopeName)
            ->willReturn($this->focuser);

        $this->focuser
            ->expects($this->once())
            ->method('setPosition')
            ->with($position);

        $params = new FocuserSetPositionParams(
            $telescopeName,
            $position
        );

        $this->job->execute($params);
    }
}
