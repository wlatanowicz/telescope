<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Focuser;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Hardware\Provider\ImagickCroppedCameraProvider;
use wlatanowicz\AppBundle\Routine\AutoFocus\SimpleRecursive;
use wlatanowicz\AppBundle\Routine\AutoFocusInterface;
use wlatanowicz\AppBundle\Routine\AutoFocusReport;
use wlatanowicz\AppBundle\Routine\Measure\StarFWHM;
use wlatanowicz\AppBundle\Routine\Provider\MeasureProvider;

class AutofocusCommand extends Command
{
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
     * @var SimpleRecursive
     */
    private $autofocus;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MeasureCommand constructor.
     * @param ImagickCroppedCameraProvider $cameraProvider
     * @param FocuserProvider $focuserProvider
     */
    public function __construct(
        ImagickCroppedCameraProvider $cameraProvider,
        FocuserProvider $focuserProvider,
        MeasureProvider $measureProvider,
        SimpleRecursive $autofocus,
        LoggerInterface $logger
    ) {
        parent::__construct(null);

        $this->cameraProvider = $cameraProvider;
        $this->focuserProvider = $focuserProvider;
        $this->measureProvider = $measureProvider;
        $this->autofocus = $autofocus;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this
            ->setName('focuser:autofocus')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', null)
            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Exposure time (seconds)', 4)
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Focuser name', null)
            ->addOption('measure', null, InputOption::VALUE_REQUIRED, 'Measure name', null)
            ->addOption('x', null, InputOption::VALUE_REQUIRED, 'Star position x coordinate', null)
            ->addOption('y', null, InputOption::VALUE_REQUIRED, 'Star position y coordinate', null)
            ->addOption('radius', 'r', InputOption::VALUE_REQUIRED, 'Measure area radius', 40)
            ->addOption('min', null, InputOption::VALUE_REQUIRED, 'Minimum focuser position', 3000)
            ->addOption('max', null, InputOption::VALUE_REQUIRED, 'Maximum focuser position', 4000)
            ->addOption('partials', null, InputOption::VALUE_REQUIRED, 'Number of autofocus itaration partials', 5)
            ->addOption('iterations', null, InputOption::VALUE_REQUIRED, 'Number of autofocus iterations', 5)
            ->addOption('threshold', null, InputOption::VALUE_REQUIRED, 'Measurement noise level threshold', 0.1)
            ->addOption('save-report', null, InputOption::VALUE_REQUIRED, 'Report file', "af-report-" . date('Y-m-d-H-i-s') . ".jpg");
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cameraName = $input->getOption('camera');
        $focuserName = $input->getOption('focuser');
        $measureName = $input->getOption('measure');

        $time = intval($input->getOption('time'), 10);

        $x = $input->getOption('x') !== null
            ? intval($input->getOption('x'), 10)
            : null;
        $y = $input->getOption('y') !== null
            ? intval($input->getOption('y'), 10)
            : null;

        $radius = intval($input->getOption('radius'), 10);

        $min = intval($input->getOption('min'), 10);
        $max = intval($input->getOption('max'), 10);

        $partials = intval($input->getOption('partials'), 10);
        $iterations = intval($input->getOption('iterations'), 10);

        $threshold = floatval($input->getOption('threshold'));

        $reportFile = $input->getOption('save-report');

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);
        $measure = $this->measureProvider->getMeasure($measureName);

        $camera->setCroping(
            $radius,
            $x,
            $y
        );

        $result = $this->autofocus->autofocus(
            $measure,
            $camera,
            $focuser,
            $partials,
            $iterations,
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