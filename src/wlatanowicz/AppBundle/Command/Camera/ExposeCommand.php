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

class ExposeCommand extends Command
{
    private $provider;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    public function __construct(CameraProvider $cameraProvider, FileSystem $fileSystem)
    {
        parent::__construct(null);

        $this->provider = $cameraProvider;
        $this->fileSystem = $fileSystem;
    }

    protected function configure()
    {
        $this
            ->setName('camera:expose')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', 'local')
            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Target position', 1)
            ->addOption('iso', null, InputOption::VALUE_REQUIRED, 'Target position', 400)
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Target position', CameraInterface::FORMAT_JPEG)
            ->addOption('filename', null, InputOption::VALUE_REQUIRED, 'Target position', "capture.jpg")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $camera = $input->getOption('camera');
        $time = intval($input->getOption('time'), 10);
        $iso = intval($input->getOption('iso'), 10);
        $format = $input->getOption('format');

        $image = $this->provider->getCamera($camera)->exposure($time, $iso, $format);
        $fileName = $input->getOption('filename');
        $this->fileSystem->filePutContents($fileName, $image->getData());
    }
}