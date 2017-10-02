<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\CameraExposeParams;

class CameraExpose extends AbstractJob
{
    /**
     * @var CameraProvider
     */
    private $provider;

    /**
     * CameraExpose constructor.
     * @param JobManager $jobManager
     * @param CameraProvider $providers
     */
    public function __construct(
        JobManager $jobManager,
        CameraProvider $provider
    ) {
        parent::__construct($jobManager);

        $this->provider = $provider;
    }


    protected function execute(
        CameraExposeParams $params
    ) {

        $fileName = $params->hasFileName()
            ? $params->getFileName()
            : "capture-" . date("Y-m-d-H-i-s");

        $cameraName = $params->hasCameraName()
            ? $params->getCameraName()
            : null;

        $time = $params->getTime();

        $camera = $this->provider->getCamera($cameraName);

        $images = $camera->exposure($time);
        $files = [];

        foreach ($images->getImages() as $image) {
            $file = $fileName . "." . $image->getFileExtension();
            $this->jobManager->saveCurrentJobResult($file, $image->getData());
            $files[] = $file;
        }

        return $files;
    }
}
