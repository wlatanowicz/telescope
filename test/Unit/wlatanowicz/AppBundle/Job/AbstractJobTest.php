<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Job;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use wlatanowicz\AppBundle\Controller\Api\Job;
use wlatanowicz\AppBundle\Data\JobStatus;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\AbstractJob;
use wlatanowicz\AppBundle\Job\JobProvider;
use wlatanowicz\AppBundle\Job\Params\JobParamsInterface;

class DummyJobParams implements JobParamsInterface
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $int;

    /**
     * DummyJobParams constructor.
     * @param string $string
     * @param int $int
     */
    public function __construct(string $string, int $int)
    {
        $this->string = $string;
        $this->int = $int;
    }

}

class DummyJob extends AbstractJob
{
    protected function execute(DummyJobParams $params): DummyJobParams
    {
        return $params;
    }
}

class AbstractJobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JobManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobManagerMock;

    /**
     * @var AbstractJob
     */
    private $job;

    /**
     * @before
     */
    public function prepare()
    {
        $this->jobManagerMock = $this->createMock(JobManager::class);

        $this->job = new DummyJob(
            $this->jobManagerMock
        );
    }

    /**
     * @test
     */
    public function itShouldStartJob()
    {
        $params = new DummyJobParams(
            "some string",
            random_int(100,99999)
        );
        $expectedResult = $params;

        $this->jobManagerMock
            ->expects($this->once())
            ->method('startJob');

        $this->jobManagerMock
            ->expects($this->once())
            ->method('finishJob')
            ->with($expectedResult);

        $result = $this->job->start($params);

    }

    /**
     * @test
     */
    public function itShouldDetermineCorrectParamsClass()
    {
        $this->assertEquals(DummyJobParams::class, $this->job->getParamsClass());
    }
}
