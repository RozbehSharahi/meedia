<?php

namespace RozbehSharahi\Meedia\Command;

use RozbehSharahi\Meedia\DummyCreator;
use RozbehSharahi\Meedia\TreeBuilder\LockTreeBuilder;
use RozbehSharahi\Meedia\TreeCreator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends AbstractCommand
{

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('meedia:install')
            ->setDescription('Install files either by lock or by live sync')
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
                null
            )
            ->addOption('update', 'u', null, 'Will recreate tree by syncing from live');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();

        if (!is_file($options['meedia-file'])) {
            throw new \Exception('Meedia is not yet configured. Please use meedia:init');
        }

        $configuration = $this->getConfiguration($options['meedia-file']);

        $output->writeln('Check configuration file...');

        if (empty($configuration->source)) {
            throw new \Exception('meedia-file:source must not be empty');
        }

        if (empty($configuration->destination)) {
            throw new \Exception('meedia-file:destination must not be empty');
        }

        if (empty($configuration->generators)) {
            throw new \Exception('meedia-file:generators must not be empty');
        }

        if (empty($configuration->treeBuilders)) {
            throw new \Exception('meedia-file:treeBuilders must not be empty');
        }

        // Set lock file either by option or configuration
        $configuration->lockFile = $options['meedia-lock-file'] ?? $configuration->lockFile ?? 'meedia-lock.json';

        if (file_exists($configuration->lockFile) && $options['update'] === false) {
            $output->writeln('Get file tree from lock file...');
            $treeCreator = new TreeCreator([new LockTreeBuilder($configuration)]);
            $tree = $treeCreator->create();
        } else {
            $output->writeln('Get file tree from live...');
            $treeCreator = new TreeCreator($this->getTreeBuilders($configuration));
            $tree = $treeCreator->create();
            $this->createLock($tree, $configuration->lockFile);
        }

        $output->writeln('Create dummy files...');

        $dummyCreator = new DummyCreator($configuration->destination, $tree, $this->getGenerators($configuration));

        $dummyCreator->create();

        $output->writeln('<info>Dummies have been created</info>');
    }

    /**
     * @param $tree
     * @param string $lockFile
     */
    protected function createLock(array $tree, string $lockFile)
    {
        file_put_contents($lockFile, json_encode($tree, JSON_PRETTY_PRINT));
    }

    /**
     * Get generators by configuration
     *
     * @param \stdClass $configuration
     * @return array
     */
    protected function getGenerators(\stdClass $configuration)
    {
        $generators = [];
        foreach ($configuration->generators as $generator) {
            $generators[] = new $generator;
        }
        return $generators;
    }

    /**
     * @param $configuration
     * @return array
     */
    protected function getTreeBuilders($configuration): array
    {
        return array_map(function ($treeBuilderClass) use ($configuration) {
            return new $treeBuilderClass($configuration);
        }, $configuration->treeBuilders);
    }
}