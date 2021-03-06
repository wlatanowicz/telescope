<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Api;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use wlatanowicz\AppBundle\Controller\Api\Job;
use wlatanowicz\AppBundle\Data\JobStatus;
use wlatanowicz\AppBundle\Data\RPCResponse;
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

class DummyJobResult
{
    /**
     * @var int
     * @Type("int")
     */
    private $int;

    /**
     * @var string
     * @Type("string")
     */
    private $string;

    /**
     * DummyJobResult constructor.
     * @param int $int
     * @param string $string
     */
    public function __construct(int $int, string $string)
    {
        $this->int = $int;
        $this->string = $string;
    }
}

class JobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JobProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobProviderMock;

    /**
     * @var JobManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobManagerMock;

    /**
     * @var Serializer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * @var FileSystem|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fileSystemMock;

    /**
     * @var Job
     */
    private $controller;

    /**
     * @var AbstractJob|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jobMock;

    /**
     * @before
     */
    public function prepare()
    {
        $this->jobProviderMock = $this->createMock(JobProvider::class);
        $this->jobManagerMock = $this->createMock(JobManager::class);
        $this->serializerMock = $this->createMock(Serializer::class);
        $this->fileSystemMock = $this->createMock(FileSystem::class);
        $this->jobMock = $this->createMock(AbstractJob::class);

        $this->controller = new Job(
            $this->jobProviderMock,
            $this->jobManagerMock,
            $this->serializerMock,
            $this->fileSystemMock
        );
    }

    /**
     * @test
     */
    public function itShouldStartJob()
    {
        $jobId = "job-id";
        $sessionId = "session-id";
        $jobName = "job-name";

        $params = [
            "string" => "some string",
            "int" => 2443,
        ];

        $json = \json_encode([
            "method" => $jobName,
            "params" => $params,
        ]);

        $jobParams = new DummyJobParams(
            "some string",
            2443
        );

        $jobResult = new DummyJobResult(
            12344,
            "another string"
        );

        $jobStatus = new JobStatus($sessionId, $jobId);
        $jobStatus->setResult($jobResult);

        $request = new Request([], [], [], [], [], [], $json);

        $this->jobProviderMock
            ->expects($this->once())
            ->method('getJob')
            ->with($jobName)
            ->willReturn($this->jobMock);

        $this->serializerMock
            ->expects($this->once())
            ->method('fromArray')
            ->with(
                $params,
                DummyJobParams::class
            )
            ->willReturn(
                $jobParams
            );

        $this->jobMock
            ->expects($this->once())
            ->method('start')
            ->with($jobParams)
            ->willReturn($jobStatus);

        $this->jobMock
            ->expects($this->once())
            ->method('getParamsClass')
            ->willReturn(DummyJobParams::class);

        $expectedResponsePayload = \json_encode([
            "some-json" => "value",
        ]);

        $this->serializerMock
            ->expects($this->once())
            ->method('serialize')
            ->with(
                RPCResponse::initWithResult($jobStatus),
                'json'
            )
            ->willReturn($expectedResponsePayload);

        $expectedResult = new JsonResponse(
            $expectedResponsePayload,
            200,
            [],
            true
        );

        $result = $this->controller->start(
            $request,
            $jobId,
            $sessionId
        );

        $this->assertEquals($expectedResult, $result);
    }
}
