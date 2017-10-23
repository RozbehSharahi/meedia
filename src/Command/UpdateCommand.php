<?php

namespace RozbehSharahi\Meedia\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setDescription('Install files by syncing live.');
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
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
    }
}