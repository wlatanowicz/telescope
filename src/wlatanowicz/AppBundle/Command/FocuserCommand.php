<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\AppBundle\Hardware\Provider\FocuserProvider;

class FocuserCommand extends Command
{
    private $provider;

    public function __construct(FocuserProvider $provider)
    {
        parent::__construct(null);

        $this->provider = $provider;
    }

    protected function configure()
    {
        $this
            ->setName('focuser:goto')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->provider->getFocuser('node')->setPosition(5000);
    }
}