<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Telescope;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Job\Params\TelescopeGetPositionParams;
use wlatanowicz\AppBundle\Job\TelescopeGetPosition;

class PositionCommand extends Command
{
    /**
     * @var TelescopeGetPosition
     */
    private $job;

    public function __construct(TelescopeGetPosition $job)
    {
        parent::__construct(null);

        $this->job = $job;
    }

    protected function configure()
    {
        $this
            ->setName('telescope:position')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('telescope', null, InputOption::VALUE_REQUIRED, 'Telescope name', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $telescopeName = $input->getOption('telescope');

        $result = $this->job->execute(new TelescopeGetPositionParams(
            $telescopeName
        ));
    }
}