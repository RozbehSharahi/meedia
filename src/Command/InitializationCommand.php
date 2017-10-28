<?php

namespace RozbehSharahi\Meedia\Command;

use RozbehSharahi\Meedia\DummyGenerator\ImageDummyGenerator;
use RozbehSharahi\Meedia\TreeBuilder\ImageTreeBuilder;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class InitializationCommand extends AbstractCommand
{

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('meedia:init')
            ->setDescription('Create configuration file for project')
            ->addArgument('host', InputArgument::OPTIONAL, 'Host of live website')
            ->addArgument('user', InputArgument::OPTIONAL, 'Username to connect')
            ->addArgument('source', InputArgument::OPTIONAL, 'Source of media')
            ->addArgument('destination', InputArgument::OPTIONAL, 'Destination for dummies')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'Password (optional)');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArguments();
        $options = $input->getOptions();

        if (empty($arguments['host'])) {
            $arguments['host'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('SSH host name: ')
            );
        }

        if (empty($arguments['user'])) {
            $arguments['user'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('SSH user: ')
            );
        }

        if (empty($options['password'])) {
            $options['password'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('For password access instead of user agent, type in a password, else leave blank: ')
            );
        }

        if (empty($arguments['source'])) {
            $arguments['source'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('Where shall meedia get your media (default: /var/www/html/img/): ', '/var/www/html/img/')
            );
        }

        if (empty($arguments['destination'])) {
            $arguments['destination'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('Where shall meedia put your dummies (default: meedia-sync/): ', 'meedia-sync/')
            );
        }

        file_put_contents('meedia.json', json_encode([
            'host' => $arguments['host'],
            'user' => $arguments['user'],
            'source' => $arguments['source'],
            'destination' => $arguments['destination'],
            'generators' => [
                ImageDummyGenerator::class
            ],
            'treeBuilders' => [
                ImageTreeBuilder::class
            ]
        ], JSON_PRETTY_PRINT));

        if (!empty($options['password'])) {
            file_put_contents('meedia-secret.json', json_encode([
                'password' => $options['password']
            ], JSON_PRETTY_PRINT));
        }

        $output->writeln('meedia.json file was generated');
    }
}