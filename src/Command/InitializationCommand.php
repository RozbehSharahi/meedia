<?php

namespace RozbehSharahi\Meedia\Command;

use RozbehSharahi\Meedia\DummyGenerator\ImageDummyGenerator;
use RozbehSharahi\Meedia\DummyGenerator\TextFileDummyGenerator;
use RozbehSharahi\Meedia\TreeBuilder\ImageTreeBuilder;
use RozbehSharahi\Meedia\TreeBuilder\TextFileTreeBuilder;
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
            ->addOption(
                'host',
                null,
                InputOption::VALUE_OPTIONAL,
                'Host of live website',
                null
            )
            ->addOption(
                'user',
                null,
                InputOption::VALUE_OPTIONAL,
                'Username to connect',
                null
            )
            ->addOption(
                'port',
                null,
                InputOption::VALUE_OPTIONAL,
                'Port',
                22
            )
            ->addOption(
                'source',
                null,
                InputOption::VALUE_OPTIONAL,
                'Source of media',
                null
            )
            ->addOption(
                'destination',
                null,
                InputOption::VALUE_OPTIONAL,
                'Destination for dummies',
                null
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_OPTIONAL,
                'Password (optional)',
                null
            )
            ->addOption(
                'meedia-file',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Config file (default: meedia.json)',
                'meedia.json'
            )
            ->addOption(
                'meedia-secret-file',
                null,
                InputOption::VALUE_OPTIONAL,
                'Meedia secret config file (default: meedia-secret.json)',
                'meedia-secret.json'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();

        if (empty($options['host'])) {
            $options['host'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('SSH host name: ')
            );
        }

        if (empty($options['user'])) {
            $options['user'] = $this->getQuestionHelper()->ask(
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

        if (empty($options['source'])) {
            $options['source'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('Where shall meedia get your media (default: /var/www/html/img/): ', '/var/www/html/img/')
            );
        }

        if (empty($options['destination'])) {
            $options['destination'] = $this->getQuestionHelper()->ask(
                $input,
                $output,
                new Question('Where shall meedia put your dummies (default: meedia-sync/): ', 'meedia-sync/')
            );
        }

        file_put_contents($options['meedia-file'], json_encode([
            'host' => $options['host'],
            'user' => $options['user'],
            'source' => $options['source'],
            'destination' => $options['destination'],
            'port' => (int)$options['port'],
            'generators' => [
                ImageDummyGenerator::class,
                TextFileDummyGenerator::class,
            ],
            'treeBuilders' => [
                ImageTreeBuilder::class,
                TextFileTreeBuilder::class
            ]
        ], JSON_PRETTY_PRINT));

        if (!empty($options['password'])) {
            file_put_contents($options['meedia-secret-file'], json_encode([
                'password' => $options['password']
            ], JSON_PRETTY_PRINT));
        }

        $output->writeln($input->getOption('meedia-file') . ' file generated successfully');
    }
}