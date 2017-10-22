<?php

namespace RozbehSharahi\Meedia\Command;

use RozbehSharahi\Meedia\DummyCreator;
use Ssh\Session;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends AbstractCommand
{

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('meedia:sync')
            ->setDescription('Sync files from live to local');
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

        $ssh = $this->getSsh($configuration);

        $output->writeln('Check if live dependencies (f.i. ImageMagick)...');

        $this->assertLive($ssh);

        $output->writeln('Get file tree...');

        if ($treeLock = $this->getTreeLock()) {
            $tree = $treeLock;
        } else {
            $tree = $this->getTree($ssh, $configuration->source);
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
     * @param string $source
     * @return array
     */
    protected function getTree(Session $ssh, $source)
    {
        $fileDescriptions = str_replace('||||' . PHP_EOL, '||||', trim($ssh->getExec()
            ->run('cd ' . $source . ' && find . -type f \( -name "*.png" -o -name "*.gif" -o -name "*.jpg" \) -exec identify -format "%w||||%h||||" {} \; -exec echo {} \;')));

        return array_map(function ($fileDescription) {
            $info = explode('||||', $fileDescription);

            $width = $info[0];
            $height = $info[1];

            // gifs will return for every frame width, and height, therefor we have to take the last part of the array
            // to get the path
            $path = $info[count($info) - 1];

            // Assert correct format
            if (empty($width) || empty($height) || empty($path)) {
                throw new \Exception('File description: ' . $fileDescription . ' could not be interpreted.');
            }

            return [
                'width' => $width,
                'height' => $height,
                'path' => $path
            ];
        }, explode(PHP_EOL, $fileDescriptions));
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