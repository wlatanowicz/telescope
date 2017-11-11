<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Job\Params\AutofocusParams;
use wlatanowicz\AppBundle\Routine\ImageProcessing\AutoFocusReportGenerator;
use wlatanowicz\AppBundle\Routine\Provider\AutoFocusProvider;
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
     * @var AutoFocusProvider
     */
    private $autofocusProvider;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var AutoFocusReportGenerator
     */
    private $reportGenerator;

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
     * @param AutoFocusProvider $autoFocusProvider
     * @param FileSystem $fileSystem
     * @param AutoFocusReportGenerator $reportGenerator
     * @param LoggerInterface $logger
     */
    public function __construct(
        JobManager $jobManager,
        CameraProvider $cameraProvider,
        FocuserProvider $focuserProvider,
        MeasureProvider $measureProvider,
        AutoFocusProvider $autoFocusProvider,
        FileSystem $fileSystem,
        AutoFocusReportGenerator $reportGenerator,
        LoggerInterface $logger
    ) {
        parent::__construct($jobManager);
        $this->cameraProvider = $cameraProvider;
        $this->focuserProvider = $focuserProvider;
        $this->measureProvider = $measureProvider;
        $this->autofocusProvider = $autoFocusProvider;
        $this->fileSystem = $fileSystem;
        $this->reportGenerator = $reportGenerator;
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

        $autofocusName = $params->hasAutofocusName()
            ? $params->getAutofocusName()
            : null;

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);
        $measure = $this->measureProvider->getMeasure($measureName);
        $autofocus = $this->autofocusProvider->getAutoFocus($autofocusName);

        $starRadius = $params->getRadius();
        $starX = $params->hasX() ? $params->getX() : null;
        $starY = $params->hasY() ? $params->getY() : null;

        $this->reportGenerator->setStar(
            $starRadius,
            $starX,
            $starY
        );

        $measure->setOptions([
            'starRadius' => $starRadius,
            'starX' => $starX,
            'starY' => $starY,
        ]);

        $options = [
            'partials' => $params->getPartials(),
            'iterations' => $params->getIterations(),
            'tries' => $params->getTries(),
        ];

        $result = $autofocus->autofocus(
            $measure,
            $camera,
            $focuser,
            $params->getMin(),
            $params->getMax(),
            $params->getTime(),
            $options
        );

        $focuser->setPosition($result->getMaximum()->getPosition());

        $reportfilename = $params->hasReportFile()
            ? $params->getReportFile()
            : "af-report-" . date('Y-m-d-H-i-s') . ".jpeg";

        $report = $this->reportGenerator->generateReport($result);
        $this->fileSystem->filePutContents(
            $this->jobManager->getCurrentJobResultDirPath() . '/' . $reportfilename,
            $report->getImageBlob()
        );
    }
}
