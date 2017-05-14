<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Telescope;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Provider\TelescopeProvider;
use wlatanowicz\AppBundle\Job\Params\TelescopeSetPositionParams;
use wlatanowicz\AppBundle\Job\TelescopeSetPosition;

class GotoCommand extends Command
{
    /**
     * @var TelescopeSetPosition
     */
    private $job;

    public function __construct(TelescopeSetPosition $job)
    {
        parent::__construct(null);

        $this->job = $job;
    }

    protected function configure()
    {
        $this
            ->setName('telescope:goto')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('telescope', null, InputOption::VALUE_REQUIRED, 'Telescope name', null)
            ->addOption('right-ascension', 'ra', InputOption::VALUE_REQUIRED, 'Target position', 0)
            ->addOption('declination', 'dec', InputOption::VALUE_REQUIRED, 'Target position', 0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $telescopeName = $input->getOption('telescope');

        $ra = floatval($input->getOption('right-ascension'));
        $dec = floatval($input->getOption('declination'));
        $coordinates = new Coordinates($ra, $dec);

        $this->job->execute(new TelescopeSetPositionParams(
            $telescopeName,
            $coordinates,
            null
        ));
    }
}