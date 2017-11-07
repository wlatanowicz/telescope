<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Hardware\Provider\ImagickCroppedCameraProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\AutofocusParams;
use wlatanowicz\AppBundle\Routine\AutoFocus\SimpleRecursive;
use wlatanowicz\AppBundle\Routine\AutoFocusInterface;
use wlatanowicz\AppBundle\Routine\AutoFocusReport;
use wlatanowicz\AppBundle\Routine\Measure\StarFWHM;
use wlatanowicz\AppBundle\Routine\Provider\MeasureProvider;

class Autofocus extends AbstractJob
{
    /**
     * @var CameraProvider
     */
    private $cameraProvider;

    /**
     * @var FocuserProvider
     */
    private $focuserProvider;

    /**
     * @var MeasureProvider
     */
    private $measureProvider;

    /**
     * @var AutoFocusInterface
     */
    private $autofocus;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Autofocus constructor.
     * @param JobManager $jobManager
     * @param CameraProvider $cameraProvider
     * @param FocuserProvider $focuserProvider
     * @param MeasureProvider $measureProvider
     * @param AutoFocusInterface $autofocus
     * @param LoggerInterface $logger
     */
    public function __construct(
        JobManager $jobManager,
        CameraProvider $cameraProvider,
        FocuserProvider $focuserProvider,
        MeasureProvider $measureProvider,
        AutoFocusInterface $autofocus,
        FileSystem $fileSystem,
        LoggerInterface $logger
    ) {
        parent::__construct($jobManager);
        $this->cameraProvider = $cameraProvider;
        $this->focuserProvider = $focuserProvider;
        $this->measureProvider = $measureProvider;
        $this->autofocus = $autofocus;
        $this->fileSystem = $fileSystem;
        $this->logger = $logger;
    }

    protected function execute(
        AutofocusParams $params
    ) {
        $cameraName = $params->hasCameraName()
            ? $params->getCameraName()
            : null;

        $focuserName = $params->hasFocuserName()
            ? $params->getFocuserName()
            : null;

        $measureName = $params->hasMeasureName()
            ? $params->getMeasureName()
            : null;

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);
        $measure = $this->measureProvider->getMeasure($measureName);

        /**
         * @var $measure StarFWHM
         */
        $measure->setStar(
            $params->getRadius(),
            $params->hasX() ? $params->getX() : null,
            $params->hasY() ? $params->getY() : null
        );

        /**
         * @var $autofocus SimpleRecursive
         */
        $autofocus = $this->autofocus;
        $autofocus->setPartials($params->getPartials());
        $autofocus->setIterations($params->getIterations());
        $autofocus->setTriesArray($params->getTries());

        $result = $this->autofocus->autofocus(
            $measure,
            $camera,
            $focuser,
            $params->getMin(),
            $params->getMax(),
            $params->getTime()
        );

        $focuser->setPosition($result->getMaximum()->getPosition());

        $reportfilename = $params->hasReportFile()
            ? $params->getReportFile()
            : "af-report-" . date('Y-m-d-H-i-s') . ".jpeg";

        $reporter = new AutoFocusReport();
        $report = $reporter->generateReport($result);
        $this->fileSystem->filePutContents(
            $this->jobManager->getCurrentJobResultDirPath() . '/' . $reportfilename,
            $report->getImageBlob()
        );
    }
}
