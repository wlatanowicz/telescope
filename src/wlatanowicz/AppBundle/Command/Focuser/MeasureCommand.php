<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Focuser;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Data\AutofocusPoint;
use wlatanowicz\AppBundle\Data\AutofocusResult;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Routine\ImageProcessing\AutoFocusReportGenerator;
use wlatanowicz\AppBundle\Routine\Measure\StarFWHM;

class MeasureCommand extends Command
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AutoFocusReportGenerator
     */
    private $reportGenerator;

    /**
     * MeasureCommand constructor.
     * @param CameraProvider $cameraProvider
     * @param FocuserProvider $focuserProvider
     */
    public function __construct(
        CameraProvider $cameraProvider,
        FocuserProvider $focuserProvider,
        AutoFocusReportGenerator $reportGenerator,
        LoggerInterface $logger
    ) {
        parent::__construct(null);

        $this->cameraProvider = $cameraProvider;
        $this->focuserProvider = $focuserProvider;
        $this->reportGenerator = $reportGenerator;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this
            ->setName('focuser:measure')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', null)
            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Exposure time (seconds)', 4)
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Focuser name', null)
            ->addOption('x', null, InputOption::VALUE_REQUIRED, 'Star position x coordinate', null)
            ->addOption('y', null, InputOption::VALUE_REQUIRED, 'Star position y coordinate', null)
            ->addOption('radius', 'r', InputOption::VALUE_REQUIRED, 'Measure area radius', 40)
            ->addOption('threshold', null, InputOption::VALUE_REQUIRED, 'Measurement noise level threshold', 0.1)
            ->addOption('save-report', null, InputOption::VALUE_REQUIRED, 'Report file', "m-report-" . date('Y-m-d-H-i-s') . ".jpeg");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cameraName = $input->getOption('camera');
        $focuserName = $input->getOption('focuser');

        $time = intval($input->getOption('time'), 10);

        $x = $input->getOption('x') !== null
            ? intval($input->getOption('x'), 10)
            : null;
        $y = $input->getOption('y') !== null
            ? intval($input->getOption('y'), 10)
            : null;

        $radius = intval($input->getOption('radius'), 10);

        $threshold = floatval($input->getOption('threshold'));

        $reportFile = $input->getOption('save-report');

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);

        $measure = new StarFWHM($threshold);
        $measure->setStar(
            $radius,
            $x,
            $y
        );

        $this->reportGenerator->setStar(
            $radius,
            $x,
            $y
        );


        $image = $camera->exposure($time);

        $measureValue = $measure->measure($image);

        $point = new AutofocusPoint(
            $focuser->getPosition(),
            $measureValue,
            $image
        );

        $result = new AutofocusResult(
            $point,
            [$point]
        );

        echo "Measured value: {$measureValue}\n";

        if ($reportFile !== null) {
            $report = $this->reportGenerator->generateReport($result);
            file_put_contents($reportFile, $report->getImageBlob());
        }
    }
}
