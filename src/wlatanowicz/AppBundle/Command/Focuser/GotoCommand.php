<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Focuser;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Job\FocuserSetPosition;
use wlatanowicz\AppBundle\Job\Params\FocuserSetPositionParams;

class GotoCommand extends Command
{
    /**
     * @var FocuserSetPosition
     */
    private $job;

    public function __construct(FocuserSetPosition $job)
    {
        parent::__construct(null);

        $this->job = $job;
    }

    protected function configure()
    {
        $this
            ->setName('focuser:goto')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('focuser', null, InputOption::VALUE_REQUIRED, 'Focuser name', 'node')
            ->addOption('position', null, InputOption::VALUE_REQUIRED, 'Target position', 0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $focuserName = $input->getOption('focuser');
        $position = intval($input->getOption('position'), 10);

        $this->job->start(new FocuserSetPositionParams(
            $focuserName,
            $position
        ));
    }
}