<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Camera;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Job\CameraExpose;
use wlatanowicz\AppBundle\Job\Params\CameraExposeParams;

class ExposeCommand extends Command
{
    /**
     * @var CameraExpose
     */
    private $job;

    public function __construct(CameraExpose $job)
    {
        parent::__construct(null);

        $this->job = $job;
    }

    protected function configure()
    {
        $this
            ->setName('camera:expose')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('camera', null, InputOption::VALUE_REQUIRED, 'Camera name', null)
            ->addOption('time', null, InputOption::VALUE_REQUIRED, 'Exposure time (seconds)', 1)
            ->addOption('filename', null, InputOption::VALUE_REQUIRED, 'Target file', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $camera = $input->getOption('camera');
        $time = floatval($input->getOption('time'));
        $filename = $input->getOption('filename');

        $this->job->start(new CameraExposeParams(
            $camera,
            $time,
            $filename
        ));
    }
}