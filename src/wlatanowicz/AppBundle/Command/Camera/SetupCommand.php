<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Camera;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Hardware\CameraInterface;
use wlatanowicz\AppBundle\Hardware\Helper\FileSystem;
use wlatanowicz\AppBundle\Hardware\Provider\CameraProvider;

class SetupCommand extends Command
{
    /**
     * @var CameraProvider
     */
    private $provider;

    public function __construct(CameraProvider $cameraProvider)
    {
        parent::__construct(null);

        $this->provider = $cameraProvider;
    }

    protected function configure()
    {
        $this
            ->setName('camera:setup')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', 'remote')
            ->addOption('iso', null, InputOption::VALUE_REQUIRED, 'Target position', null)
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Target position', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cameraName = $input->getOption('camera');
        $isoStr = $input->getOption('iso');
        $format = $input->getOption('format');

        $camera = $this->provider->getCamera($cameraName);

        if ($isoStr !== null) {
            $camera->setIso(intval($isoStr, 10));
        }

        if ($format !== null) {
            $camera->setFormat($format);
        }
    }
}