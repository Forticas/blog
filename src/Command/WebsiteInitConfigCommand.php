<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Config;

#[AsCommand(
    name: 'website:init-config',
    description: 'Initialise website configuration',
)]
class WebsiteInitConfigCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach (Config::Names as $name => $label) {
            $config = new Config();
            $config->setName($name);
            $config->setLabel($label);
            $this->entityManager->persist($config);
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success('The init is processed successfully.');

        return Command::SUCCESS;
    }
}
