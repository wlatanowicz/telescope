<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Autofocus;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;
use wlatanowicz\AppBundle\Hardware\Provider\ImagickCameraProvider;
use wlatanowicz\AppBundle\Routine\AutoFocus;
use wlatanowicz\AppBundle\Routine\MeasureStarDiameter;
use wlatanowicz\AppBundle\Routine\MeasureStarFWHM;

class AutofocusCommand extends Command
{
    /**
     * @var ImagickCameraProvider
     */
    private $cameraProvider;

    /**
     * @var FocuserProvider
     */
    private $focuserProvider;

    /**
     * MeasureCommand constructor.
     * @param ImagickCameraProvider $cameraProvider
     * @param FocuserProvider $focuserProvider
     */
    public function __construct(ImagickCameraProvider $cameraProvider, FocuserProvider $focuserProvider)
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
            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Target position', 0)
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Target position', 'sim')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cameraName = $input->getOption('camera');
        $time = intval($input->getOption('time'), 10);
        $focuserName = $input->getOption('focuser');

        $camera = $this->cameraProvider->getCamera($cameraName);
        $focuser = $this->focuserProvider->getFocuser($focuserName);

        $autofocus = new AutoFocus(
            new MeasureStarFWHM(0.1),
            $camera,
            $focuser,
            5,
            5
        );

        $autofocus->autofocus(
            3000,
            4000,
            $time
        );

    }
}