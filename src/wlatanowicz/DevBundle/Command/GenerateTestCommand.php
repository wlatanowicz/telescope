<?php
declare(strict_types = 1);

namespace wlatanowicz\DevBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use wlatanowicz\DevBundle\Generator\TestGenerator;

class GenerateTestCommand extends Command
{
    /**
     * @var TestGenerator
     */
    private $generator;

    public function __construct(TestGenerator $generator)
    {
        parent::__construct(null);

        $this->generator = $generator;
    }

    protected function configure()
    {
        $this
            ->setName('generate:test')
            ->setDescription('Creates new unit test.')
            ->setHelp("This command allows you to create unit test scaffold...")
            ->addOption('class', null, InputOption::VALUE_REQUIRED, 'Class-Under-Test name', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getOption('class');
        echo "Class: " . $class . "\n";

        $this->generator->generate($class);
    }
}