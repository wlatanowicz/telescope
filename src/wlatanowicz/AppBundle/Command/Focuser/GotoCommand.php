<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Focuser;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;

class GotoCommand extends Command
{
    private $provider;

    public function __construct(FocuserProvider $cameraProvider)
    {
        parent::__construct(null);

        $this->provider = $cameraProvider;
    }

    protected function configure()
    {
        $this
            ->setName('focuser:goto')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Focuser name', 'node')
            ->addOption('position', null, InputOption::VALUE_REQUIRED, 'Target position', 0)
            ->addOption('wait', null, InputOption::VALUE_REQUIRED, 'Wait for finish', true)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $focuser = $input->getOption('focuser');
        $position = intval($input->getOption('position'), 10);
        $wait = filter_var($input->getOption('wait'), FILTER_VALIDATE_BOOLEAN);
        $this->provider->getFocuser($focuser)->setPosition($position, $wait);
    }
}