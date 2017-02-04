<?php

namespace Unit\wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Provider\TelescopeProvider;
use wlatanowicz\AppBundle\Hardware\TelescopeInterface;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\TelescopeSetPositionParams;
use wlatanowicz\AppBundle\Job\TelescopeSetPosition;

class TelescopeSetPositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TelescopeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $telescope;

    /**
     * @var TelescopeProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $provider;

    /**
     * @var JobManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobManager;

    /**
     * @var TelescopeSetPosition
     */
    private $job;

    /**
     * @before
     */
    public function prepare()
    {
        $this->telescope = $this->createMock(TelescopeInterface::class);
        $this->provider = $this->createMock(TelescopeProvider::class);
        $this->jobManager = $this->createMock(JobManager::class);

        $this->job = new TelescopeSetPosition($this->jobManager, $this->provider);
    }

    /**
     * @test
     */
    public function itShouldSetPosition()
    {
        $telescopeName = 'scope';
        $ra = rand(0, 12000) / 1000;
        $dec = (rand(0, 180000) / 1000) - 90;

        $coordinates = new Coordinates($ra, $dec);

        $this->provider
            ->expects($this->once())
            ->method('getTelescope')
            ->with($telescopeName)
            ->willReturn($this->telescope);

        $this->telescope
            ->expects($this->once())
            ->method('setPosition')
            ->with($coordinates);

        $params = new TelescopeSetPositionParams(
            $telescopeName,
            $coordinates
        );

        $this->job->start($params);
    }
}
