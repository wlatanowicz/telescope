<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

use Psr\Log\LoggerInterface;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Hardware\Provider\ImagickCroppedCameraProvider;
use wlatanowicz\AppBundle\Helper\JobManager;
use wlatanowicz\AppBundle\Routine\AutoFocus\SimpleRecursive;
use wlatanowicz\AppBundle\Routine\AutoFocusInterface;
use wlatanowicz\AppBundle\Routine\AutoFocusReport;
use wlatanowicz\AppBundle\Routine\Provider\MeasureProvider;

class Autofocus extends AbstractJob
{
    /**
     * @var JobManager
     */
    private $jobManager;

    /**
     * @var ImagickCroppedCameraProvider
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Autofocus constructor.
     * @param JobManager $jobManager
     * @param ImagickCroppedCameraProvider $cameraProvider
     * @param FocuserProvider $focuserProvider
     * @param MeasureProvider $measureProvider
     * @param AutoFocusInterface $autofocus
     * @param LoggerInterface $logger
     */
    public function __construct(
        JobManager $jobManager,
        ImagickCroppedCameraProvider $cameraProvider,
        FocuserProvider $focuserProvider,
        MeasureProvider $measureProvider,
        AutoFocusInterface $autofocus,
        LoggerInterface $logger
    ) {
        $this->jobManager = $jobManager;
        $this->cameraProvider = $cameraProvider;
        $this->focuserProvider = $focuserProvider;
        $this->measureProvider = $measureProvider;
        $this->autofocus = $autofocus;
        $this->logger = $logger;
    }

    protected function execute(
        string $cameraName = null,
        string $focuserName = null,
        string $measureName = null,
        int $min,
        int $max,
        int $time,
        int $partials,
        int $iterations,
        array $tries,
        int $radius,
        int $x = null,
        int $y = null,
        string $reportFile = null
    ) {
        $this->jobManager->startJob();

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);
        $measure = $this->measureProvider->getMeasure($measureName);

        $camera->setCroping(
            $radius,
            $x,
            $y
        );

        /**
         * @var $autofocus SimpleRecursive
         */
        $autofocus = $this->autofocus;
        $autofocus->setPartials($partials);
        $autofocus->setIterations($iterations);
        $autofocus->setTriesArray($tries);

        $result = $this->autofocus->autofocus(
            $measure,
            $camera,
            $focuser,
            $min,
            $max,
            $time
        );

        $focuser->setPosition($result->getMaximum()->getPosition());

        if ($reportFile !== null) {
            $reporter = new AutoFocusReport();
            $report = $reporter->generateReport($result);
            file_put_contents($reportFile, $report->getImageBlob());
        }

    }
}
