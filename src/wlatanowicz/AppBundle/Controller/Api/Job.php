<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Controller\Api;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\JobProvider;

class Job
{
    /**
     * @var JobProvider
     */
    private $jobProvider;

    /**
     * @var JobManager
     */
    private $jobManager;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * Job constructor.
     * @param JobProvider $jobProvider
     * @param JobManager $jobManager
     * @param Serializer $serializer
     * @param FileSystem $fileSystem
     */
    public function __construct(
        JobProvider $jobProvider,
        JobManager $jobManager,
        Serializer $serializer,
        FileSystem $fileSystem
    ) {
        $this->jobProvider = $jobProvider;
        $this->jobManager = $jobManager;
        $this->serializer = $serializer;
        $this->fileSystem = $fileSystem;
    }

    public function start(Request $request, string $jobId = null, string $sessionId = null)
    {
        $input = \json_decode($request->getContent(), true);

        $job = $this->jobProvider->getJob($input['method']);
        $params = $this->serializer->fromArray(
            $input['params'],
            $job->getParamsClass()
        );

        $result = $job->start($params, $jobId, $sessionId);

        $serializedResult = $this->serializer->serialize(
            $result,
            'json'
        );

        return new JsonResponse($serializedResult, 200, [], true);
    }

    public function status(string $jobId, string $sessionId)
    {
        $json = $this->fileSystem->fileGetContents(
            $this->jobManager->getJobStatusFilePath(
                $jobId,
                $sessionId
            )
        );

        return new JsonResponse($json, 200, [], true);
    }

    public function logs(string $jobId, string $sessionId)
    {
        $data = $this->fileSystem->fileGetContents(
            $this->jobManager->getJobLogFilePath(
                $jobId,
                $sessionId
            )
        );

        return new Response(
            $data,
            200,
            [
                "Content-Type" => "text/plain"
            ]
        );
    }

    public function result(string $sessionId, string $filename)
    {
        $dir = $this->jobManager->getJobResultDirPath(null, $sessionId);
        $path = $dir . '/' . $filename;
        $data = $this->fileSystem->fileGetContents($path);

        $mimetype = \GuzzleHttp\Psr7\mimetype_from_filename($filename) ?? "application/octet-stream";

        return new Response(
            $data,
            200,
            [
                "Content-Type" => $mimetype
            ]
        );
    }
}
