<?php

namespace RozbehSharahi\Meedia\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestConnectionCommand extends AbstractCommand
{

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('meedia:test-connection')
            ->setDescription('Test configured connection');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_file('meedia.json')) {
            throw new \Exception('Meedia is not yet configured. Please use meedia:init');
        }

        $configuration = $this->getConfiguration();

        if (empty($configuration->host)) {
            throw new \Exception('Host not found in meedia.json');
        }

        if (empty($configuration->user)) {
            throw new \Exception('User not found in meedia.json');
        }

        $ssh = $this->getSsh($configuration);

        $output->writeln('Trying to read working directory...');

        try {
            $output->writeln('<fg=green;options=bold>Success</>: Could read working directory: ' . $ssh->getExec()->run('pwd'));
        } catch (\Exception $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');
        }
    }
}