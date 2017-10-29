<?php

namespace RozbehSharahi\Meedia\Tests\Command;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\Command\TestConnectionCommand;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class TestConnectionCommandTest extends TestCase
{
    protected $meediaTestFilePath = __DIR__ . '/../Fixtures/meedia-test-config.json';
    protected $meediaTestLockFilePath = 'vfs://dir/does-definitely-not-exist';

    /** @test */
    public function canRunTestConnection()
    {

        vfsStream::setup('dir');

        $command = new TestConnectionCommand();

        $input = new StringInput('--meedia-file=' . $this->meediaTestFilePath);
        $output = new BufferedOutput();

        $command->run($input, $output);

        $outputContent = $output->fetch();

        self::assertContains('/home/testuser', $outputContent);
        self::assertContains('Success', $outputContent);

    }

}