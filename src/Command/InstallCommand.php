<?php

namespace RozbehSharahi\Meedia\Command;

use RozbehSharahi\Meedia\DummyCreator;
use RozbehSharahi\Meedia\TreeCreator;
use Ssh\Session;
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
                'meedia-lock.json'
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

        $output->writeln('Get file tree...');

        if (!$options['update'] && $treeLock = $this->getTreeLock($options['meedia-lock-file'])) {
            $tree = $treeLock;
        } else {
            $ssh = $this->getSsh($configuration);

            $output->writeln('Check live dependencies (f.i. ImageMagick)...');
            $this->assertLive($ssh);

            $tree = $this->getTree($ssh, $configuration);
            $output->writeln('Create lock file...');
            $this->createLock($tree, $options['meedia-lock-file']);
        }

        $output->writeln('Create dummy files...');

        $dummyCreator = new DummyCreator($configuration->destination, $tree, $this->getGenerators($configuration));

        $dummyCreator->create();

        $output->writeln('<info>Dummies have been created</info>');
    }

    /**
     * @param Session $ssh
     * @throws \Exception
     */
    protected function assertLive(Session $ssh)
    {
        if (!strpos($ssh->getExec()->run('convert -version'), 'ImageMagick') !== false) {
            throw new \Exception('convert command is not available on live. Meedia could not sync media');
        }
    }

    /**
     * @param Session $ssh
     * @param \stdClass $configuration
     * @return array
     */
    protected function getTree(Session $ssh, $configuration)
    {
        $treeCreator = new TreeCreator(array_map(function (string $treeBuilderClass) use ($ssh, $configuration) {
            return new $treeBuilderClass($configuration->source, $ssh);
        }, $configuration->treeBuilders));

        return $treeCreator->create();
    }

    /**
     * @param $tree
     * @param string $lockFile
     */
    protected function createLock($tree, string $lockFile)
    {
        file_put_contents($lockFile, json_encode($tree, JSON_PRETTY_PRINT));
    }

    /**
     * @param string $lockFile
     * @return array|null
     */
    protected function getTreeLock(string $lockFile)
    {
        if (file_exists($lockFile)) {
            return json_decode(file_get_contents($lockFile), true);
        }
        return null;
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
}