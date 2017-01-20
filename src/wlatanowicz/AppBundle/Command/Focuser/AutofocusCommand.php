<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Focuser;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Job\Autofocus;
use wlatanowicz\AppBundle\Routine\AutoFocus\SimpleRecursive;
use wlatanowicz\AppBundle\Routine\AutoFocusReport;

class AutofocusCommand extends Command
{
    /**
     * @var Autofocus
     */
    private $job;

    /**
     * AutofocusCommand constructor.
     * @param Autofocus $job
     */
    public function __construct(Autofocus $job)
    {
        parent::__construct(null);

        $this->job = $job;
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
            ->addOption('iterations', null, InputOption::VALUE_REQUIRED, 'Number of autofocus iterations', 6)
            ->addOption('tries', null, InputOption::VALUE_REQUIRED, 'Number of exposures on each position', 1)
            ->addOption('threshold', null, InputOption::VALUE_REQUIRED, 'Measurement noise level threshold', 0.1)
            ->addOption('save-report', null, InputOption::VALUE_REQUIRED, 'Report file', "af-report-" . date('Y-m-d-H-i-s') . ".jpeg");
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
        $tries = array_map(
            function ($value) {
                return intval($value, 10);
            },
            explode(",", (string)$input->getOption('tries') )
        );

        $reportFile = $input->getOption('save-report');

        $this->job->start([
            "cameraName" => $cameraName,
            "focuserName" => $focuserName,
            "measureName" => $measureName,
            "min" => $min,
            "max" => $max,
            "time" => $time,
            "partials" => $partials,
            "iterations" => $iterations,
            "tries" => $tries,
            "radius" => $radius,
            "x" => $x,
            "y" => $y,
            "reportFile" => $reportFile,
        ]);

    }
}