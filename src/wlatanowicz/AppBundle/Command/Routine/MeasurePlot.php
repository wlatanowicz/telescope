<?php

namespace wlatanowicz\AppBundle\Command\Routine;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Factory\ImagickImageFactory;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Routine\Provider\MeasureProvider;

class MeasurePlot extends Command
{
    /**
     * @var FocuserProvider
     */
    private $focuserProvider;

    /**
     * @var MeasureProvider
     */
    private $measurePovider;

    /**
     * @var CameraProvider
     */
    private $cameraProvider;

    /**
     * @var ImagickImageFactory
     */
    private $imagickImageFactory;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MeasurePlot constructor.
     * @param FocuserProvider $focuserProvider
     * @param MeasureProvider $measurePovider
     * @param CameraProvider $cameraProvider
     * @param ImagickImageFactory $imagickImageFactory
     * @param FileSystem $fileSystem
     * @param LoggerInterface $logger
     */
    public function __construct(
        FocuserProvider $focuserProvider,
        MeasureProvider $measurePovider,
        CameraProvider $cameraProvider,
        ImagickImageFactory $imagickImageFactory,
        FileSystem $fileSystem,
        LoggerInterface $logger
    ) {
        parent::__construct(null);

        $this->focuserProvider = $focuserProvider;
        $this->measurePovider = $measurePovider;
        $this->cameraProvider = $cameraProvider;
        $this->imagickImageFactory = $imagickImageFactory;
        $this->fileSystem = $fileSystem;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this
            ->setName('measure:plot')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', 'sim-real')
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Focuser name', 'sim')
            ->addOption('measure', null, InputOption::VALUE_REQUIRED, 'Measure name', 'plot')

            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Exposure time (seconds)', 0)

            ->addOption('min', null, InputOption::VALUE_REQUIRED, 'Minimum focuser position', 0)
            ->addOption('max', null, InputOption::VALUE_REQUIRED, 'Maximum focuser position', 859)
            ->addOption('step', null, InputOption::VALUE_REQUIRED, 'Number of autofocus itaration partials', 1)

            ->addOption('x', null, InputOption::VALUE_REQUIRED, 'Star position x coordinate', 967)
            ->addOption('y', null, InputOption::VALUE_REQUIRED, 'Star position y coordinate', 525)
            ->addOption('radius', 'r', InputOption::VALUE_REQUIRED, 'Measure area radius', 40)
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
        $step = intval($input->getOption('step'), 10);

        $focuser = $this->focuserProvider->getFocuser($focuserName);
        $camera = $this->cameraProvider->getCamera($cameraName);
        $measure = $this->measurePovider->getMeasure($measureName);

        $measure->setOptions([
            'starRadius' => $radius,
            'starX' => $x,
            'starY' => $y,
        ]);

        $csv = "focuser position,{$measureName} value\n";

        for ($i = $min; $i <= $max; $i += $step) {
            $focuser->setPosition($i, true);
            $images = $camera->exposure($time);
            $imagickImage = $this->imagickImageFactory->fromBinaryImages($images);
            $measurement = $measure->measure($imagickImage);

            $this->logger->info(
                "Measured image (measurement={measurement}, position={position})",
                [
                    "position" => $i,
                    "measurement" => $measurement !== null
                        ? round($measurement, 4)
                        : "NULL",
                ]
            );

            $csv .= "{$i},{$measurement}\n";
        }

        $reportfilename = "measure-plot-" . date('Y-m-d-H-i-s') . ".csv";

        $this->fileSystem->filePutContents(
            $reportfilename,
            $csv
        );
    }
}
