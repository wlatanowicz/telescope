<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Data\Coordinates;
use wlatanowicz\AppBundle\Hardware\Telescope;

class TelescopeCommand extends Command
{
    /**
     * @var Telescope
     */
    private $telescope;

    public function __construct(Telescope $telescope)
    {
        parent::__construct(null);

        $this->telescope = $telescope;
    }

    protected function configure()
    {
        $this
            ->setName('telescope:goto')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $coordinates = new Coordinates(12, 40);
        $this->telescope->setPosition($coordinates);
    }
}