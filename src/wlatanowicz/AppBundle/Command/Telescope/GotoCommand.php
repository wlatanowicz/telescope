<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command\Telescope;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Provider\TelescopeProvider;

class GotoCommand extends Command
{
    /**
     * @var TelescopeProvider
     */
    private $provider;

    public function __construct(TelescopeProvider $cameraProvider)
    {
        parent::__construct(null);

        $this->provider = $cameraProvider;
    }

    protected function configure()
    {
        $this
            ->setName('telescope:goto')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
            ->addOption('telescope', null, InputOption::VALUE_REQUIRED, 'Focuser name', 'remote')
            ->addOption('right-ascension', 'ra', InputOption::VALUE_REQUIRED, 'Target position', 0)
            ->addOption('declination', 'dec', InputOption::VALUE_REQUIRED, 'Target position', 0)
            ->addOption('wait', null, InputOption::VALUE_REQUIRED, 'Target position', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $telescope = $input->getOption('telescope');
        $wait = filter_var($input->getOption('wait'), FILTER_VALIDATE_BOOLEAN);
        $ra = floatval($input->getOption('right-ascension'));
        $dec = floatval($input->getOption('declination'));
        $coordinates = new Coordinates($ra, $dec);
        $this->provider->getTelescope($telescope)->setPosition($coordinates, $wait);
    }
}