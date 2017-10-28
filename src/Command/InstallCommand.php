<?php

namespace RozbehSharahi\Meedia\Command;

use RozbehSharahi\Meedia\DummyCreator;
use Ssh\Session;
use Symfony\Component\Console\Input\InputInterface;
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
        if (!is_file('meedia.json')) {
            throw new \Exception('Meedia is not yet configured. Please use meedia:init');
        }

        $configuration = $this->getConfiguration();

        $output->writeln('Check configuration file...');

        $this->assertConfiguration($configuration);

        $output->writeln('Get file tree...');

        if (!$input->getOption('update') && $treeLock = $this->getTreeLock()) {
            $tree = $treeLock;
        } else {
            $ssh = $this->getSsh($configuration);

            $output->writeln('Check live dependencies (f.i. ImageMagick)...');
            $this->assertLive($ssh);

            $tree = $this->getTree($ssh, $configuration);
            $output->writeln('Create lock file...');
            $this->createLock($tree);
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
     * @param $configuration
     * @throws \Exception
     */
    protected function assertConfiguration($configuration)
    {
        if (empty($configuration->source)) {
            throw new \Exception('meedia.json:source must not be empty');
        }

        if (empty($configuration->destination)) {
            throw new \Exception('meedia.json:destination must not be empty');
        }

        if (empty($configuration->generators)) {
            throw new \Exception('meedia.json:generators must not be empty');
        }
    }

    /**
     * @param Session $ssh
     * @param \stdClass $configuration
     */
    protected function getTree(Session $ssh, $configuration)
    {

    }

    /**
     * @param $tree
     */
    protected function createLock($tree)
    {
        file_put_contents('meedia-lock.json', json_encode($tree, JSON_PRETTY_PRINT));
    }

    /**
     * @return array|null
     */
    protected function getTreeLock()
    {
        if (file_exists('meedia-lock.json')) {
            return json_decode(file_get_contents('meedia-lock.json'), true);
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