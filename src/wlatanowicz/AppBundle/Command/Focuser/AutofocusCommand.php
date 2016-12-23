<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Focuser;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Data\BinaryImage;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Hardware\Provider\ImagickCroppedCameraProvider;
use wlatanowicz\AppBundle\Routine\AutoFocus;
use wlatanowicz\AppBundle\Routine\AutoFocusReport;
use wlatanowicz\AppBundle\Routine\MeasureStarDiameter;
use wlatanowicz\AppBundle\Routine\MeasureStarFWHM;

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
     * MeasureCommand constructor.
     * @param ImagickCroppedCameraProvider $cameraProvider
     * @param FocuserProvider $focuserProvider
     */
    public function __construct(ImagickCroppedCameraProvider $cameraProvider, FocuserProvider $focuserProvider)
    {
        parent::__construct(null);

        $this->cameraProvider = $cameraProvider;
        $this->focuserProvider = $focuserProvider;
    }


    protected function configure()
    {
        $this
            ->setName('focuser:autofocus')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', 'sim')
            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Target position', 4)
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Target position', 'sim')
            ->addOption('x', null, InputOption::VALUE_REQUIRED, 'Target position', null)
            ->addOption('y', null, InputOption::VALUE_REQUIRED, 'Target position', null)
            ->addOption('radius', 'r', InputOption::VALUE_REQUIRED, 'Target position', 40)
            ->addOption('min', null, InputOption::VALUE_REQUIRED, 'Target position', 3000)
            ->addOption('max', null, InputOption::VALUE_REQUIRED, 'Target position', 4000)
            ->addOption('partials', null, InputOption::VALUE_REQUIRED, 'Target position', 5)
            ->addOption('iterations', null, InputOption::VALUE_REQUIRED, 'Target position', 5)
            ->addOption('threshold', null, InputOption::VALUE_REQUIRED, 'Target position', 0.1)
            ->addOption('save-report', null, InputOption::VALUE_REQUIRED, 'Target position', null)
        ;
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

        $min = intval($input->getOption('min'), 10);
        $max = intval($input->getOption('max'), 10);

        $partials = intval($input->getOption('partials'), 10);
        $iterations = intval($input->getOption('iterations'), 10);

        $threshold = floatval($input->getOption('threshold'));

        $reportFile = $input->getOption('save-report');

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);

        $camera->setCroping(
            $radius,
            $x,
            $y
        );

        $autofocus = new AutoFocus(
            new MeasureStarFWHM($threshold),
            $camera,
            $focuser,
            $partials,
            $iterations
        );

        $result = $autofocus->autofocus(
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