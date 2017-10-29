<?php

namespace RozbehSharahi\Meedia\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends AbstractCommand
{

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('meedia:update')
            ->setDescription('Install files by syncing live.')
            ->addOption(
                'meedia-file',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Meedia config file (default meedia.json)',
                'meedia.json'
            )
            ->addOption(
                'meedia-lock-file',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Meedia lock config file (default meedia-lock.json)',
                'meedia-lock.json'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('meedia:install');

        $arguments = array(
            'command' => 'meedia:install',
            '--update' => true,
            '--meedia-file' => $input->getOption('meedia-file'),
            '--meedia-lock-file' => $input->getOption('meedia-lock-file')
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }
}