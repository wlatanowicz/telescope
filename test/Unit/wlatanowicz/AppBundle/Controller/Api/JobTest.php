<?php
declare(strict_types = 1);

namespace Unit\wlatanowicz\AppBundle\Controller\Api;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Unit\wlatanowicz\AppBundle\Controller\Api\Helper\DummyJob;
use Unit\wlatanowicz\AppBundle\Controller\Api\Helper\DummyJobParams;
use Unit\wlatanowicz\AppBundle\Controller\Api\Helper\DummyJobResult;
use wlatanowicz\AppBundle\Controller\Api\Job;
use wlatanowicz\AppBundle\Data\JobStatus;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\JobProvider;

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
     * @var DummyJob
     */
    private $job;

    /**
     * @before
     */
    public function prepare()
    {
        $this->jobProviderMock = $this->createMock(JobProvider::class);
        $this->jobManagerMock = $this->createMock(JobManager::class);
        $this->serializerMock = $this->createMock(Serializer::class);
        $this->fileSystemMock = $this->createMock(FileSystem::class);

        $this->job = new DummyJob($this->jobManagerMock);

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

        $request = new Request([], [], [], [], [], [], $json);

        $this->jobProviderMock
            ->expects($this->once())
            ->method('getJob')
            ->with($jobName)
            ->willReturn($this->job);

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

        $this->job->jobResult = $jobResult;

        $wrappedJobResult = new JobStatus();
        $wrappedJobResult->setResult($jobResult);

        $expectedResponsePayload = \json_encode([
            "some-json" => "value",
        ]);

        $this->serializerMock
            ->expects($this->once())
            ->method('serialize')
            ->with(
                $wrappedJobResult,
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

        $this->assertEquals($jobParams, $this->job->jobParams);
        $this->assertEquals($expectedResult, $result);
    }
}
