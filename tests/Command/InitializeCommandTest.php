<?php

namespace RozbehSharahi\Meedia\Tests\Command;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\Command\InitializationCommand;
use RozbehSharahi\Meedia\DummyGenerator\ImageDummyGenerator;
use RozbehSharahi\Meedia\TreeBuilder\ImageTreeBuilder;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class InitializeCommandTest extends TestCase
{

    /** @test */
    public function canInitializeMeedia()
    {
        $command = new InitializationCommand();

        vfsStream::setup('dir');

        $input = new ArrayInput([
            '--meedia-file' => 'vfs://dir/meedia-test-configuration.json',
            '--meedia-secret-file' => 'vfs://dir/meedia-test-secret-configuration.json',
            '--host' => 'localhost',
            '--user' => 'testuser',
            '--port' => '2222',
            '--source' => '~/test-server',
            '--destination' => 'vfs://dir/test-destination',
            '--password' => 'testpass',
        ]);
        $output = new BufferedOutput();

        $command->run($input, $output);

        self::assertEquals([
            'host' => 'localhost',
            'user' => 'testuser',
            'port' => 2222,
            'source' => '~/test-server',
            'destination' => 'vfs://dir/test-destination',
            'generators' => [
                ImageDummyGenerator::class,
            ],
            'treeBuilders' => [
                ImageTreeBuilder::class,
            ]
        ], json_decode(file_get_contents('vfs://dir/meedia-test-configuration.json'), true));

        self::assertEquals([
            'password' => 'testpass'
        ], json_decode(file_get_contents('vfs://dir/meedia-test-secret-configuration.json'), true));
    }

}